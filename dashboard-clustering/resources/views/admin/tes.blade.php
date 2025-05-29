public function jalankan()
{
$data = DB::table('data_pekerja')->get()->map(function ($item) {
return [
'id' => $item->id,
'id_provinsi' => $item->id_provinsi,
'tahun' => $item->tahun,
'fitur' => [
(double) $item->garis_kemiskinan,
(double) $item->upah_minimum,
(double) $item->pengeluaran,
(double) $item->rr_upah
]
];
});

// Ambil centroid awal dari cluster_awal
$clusterAwal = DB::table('iterasi_cluster_awal')->orderBy('cluster')->get();
$centroids = [];
foreach ($clusterAwal as $item) {
$centroids[$item->cluster - 1] = [
(double) $item->garis_kemiskinan,
(double) $item->upah_minimum,
(double) $item->pengeluaran,
(double) $item->rr_upah
];
}

$maxIterations = 100;
$threshold = 0.001;

for ($iterasi = 1; $iterasi <= $maxIterations; $iterasi++) {
$clusters = [[], [], []];
$totalSSE = 0;

foreach ($data as $point) {
$distances = [];
foreach ($centroids as $centroid) {
$distances[] = $this->euclideanDistance($point['fitur'], $centroid);
}

$minIndex = array_keys($distances, min($distances))[0];
$clusters[$minIndex][] = $point;
$squaredError = pow($distances[$minIndex], 2);
$totalSSE += $squaredError;

// Simpan iterasi jarak
$idIterasiJarak = DB::table('iterasi_jarak')->insertGetId([
'id_provinsi' => $point['id_provinsi'],
'tahun' => $point['tahun'],
'jarak_c1' => $distances[0],
'jarak_c2' => $distances[1],
'jarak_c3' => $distances[2],
'c_terkecil' => $distances[$minIndex],
'cluster' => $minIndex + 1,
'jarak_minimum' => $squaredError,
'created_at' => now(),
'updated_at' => now(),
]);

}
// Simpan SSE
DB::table('iterasi_sse')->insert([
'id_iterasi_jarak' => $idIterasiJarak,
'sse' => $squaredError,
'created_at' => now(),
'updated_at' => now(),
]);

// Hitung centroid baru
$newCentroids = [];
foreach ($clusters as $index => $clusterPoints) {
if (count($clusterPoints) === 0) {
$newCentroids[$index] = $centroids[$index];
continue;
}

$sum = array_fill(0, 4, 0);
foreach ($clusterPoints as $point) {
foreach ($point['fitur'] as $i => $val) {
$sum[$i] += $val;
}
}

$newCentroid = array_map(fn($x) => $x / count($clusterPoints), $sum);
$newCentroids[$index] = $newCentroid;

// Simpan cluster baru
$lastSSEId = DB::table('iterasi_sse')->latest('id_iterasi_sse')->value('id_iterasi_sse');
DB::table('iterasi_cluster_baru')->insert([
'id_iterasi_sse' => $lastSSEId,
'cluster' => $index + 1,
'garis_kemiskinan' => $newCentroid[0],
'upah_minimum' => $newCentroid[1],
'pengeluaran' => $newCentroid[2],
'rr_upah' => $newCentroid[3],
'created_at' => now(),
'updated_at' => now(),
]);
}

// Cek konvergensi
$delta = 0;
for ($i = 0; $i < 3; $i++) {
$delta += $this->euclideanDistance($centroids[$i], $newCentroids[$i]);
}

if ($delta < $threshold) break;
else $this->hapus();
$centroids = $newCentroids;
}

// Simpan hasil akhir keanggotaan
foreach ($clusters as $clusterIndex => $clusterPoints) {
foreach ($clusterPoints as $point) {
DB::table('data_pekerja_cluster')->insert([
'cluster' => $clusterIndex + 1,
'id_provinsi' => $point['id_provinsi'],
'tahun' => $point['tahun'],
'garis_kemiskinan' => $point['fitur'][0],
'upah_minimum' => $point['fitur'][1],
'pengeluaran' => $point['fitur'][2],
'rr_upah' => $point['fitur'][3],
'created_at' => now(),
'updated_at' => now(),
]);
}
}
return redirect()->back()->with('success', 'Clustering berhasil dilakukan dan disimpan.');
}