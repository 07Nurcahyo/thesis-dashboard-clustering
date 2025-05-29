@extends('layouts_guest.template')

@section('content')
    <div class="container">

        <div class="modal fade" id="modalInfoProvinsi" tabindex="-1" role="dialog" aria-labelledby="modalInfoProvinsiLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title text-center w-100" id="modalInfoProvinsiLabel"><span id="provinsiName" class="font-weight-bold"></span></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p><strong>Provinsi:</strong> <span id="provinsiName" class="font-weight-bold"></span></p> --}}
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Kategori:</strong> <span id="clusterName"></span></p>
                                <p><strong>Tahun:</strong> <span id="year"></span></p><hr>
                                <p 
                                {{-- style="color: #dc3545" --}}
                                ><strong>Garis Kemiskinan (Rp):</strong> <span id="garisKemiskinan"></span></p>
                                <p 
                                {{-- style="color: #ffc107" --}}
                                ><strong>Upah Minimum (Rp):</strong> <span id="upahMinimum"></span></p>
                                <p 
                                {{-- style="color: #28a745" --}}
                                ><strong>Pengeluaran (Rp):</strong> <span id="pengeluaran"></span></p>
                                <p 
                                {{-- style="color: #17a2b8" --}}
                                ><strong>RR Upah per Jam (Rp):</strong> <span id="rataRataUpah"></span></p>
                            </div>
                            <div class="col-md-8">
                                <canvas id="chartProvinsi" height="20" width="40"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          

        <!-- Info boxes -->
        {{-- <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-chart-line"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Garis Kemiskinan</span>
                  <span class="info-box-number">*ini informasi*<small></small></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-money-bill-alt"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Upah Minimum</span>
                  <span class="info-box-number">*ini informasi*</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
  
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
  
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Pengeluaran</span>
                  <span class="info-box-number">*ini informasi*</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-coins"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Rata-rata Upah</span>
                  <span class="info-box-number">*ini informasi*</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div> --}}

        <!-- Main row -->
        <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
            <!-- MAP & BOX PANE -->
            <div class="card">
                <div class="card-header bg-navy">
                    <h3 class="card-title">Peta Informasi Kesejahteraan Pekerja di Indonesia berdasarkan Provinsi</h3>
                    <div class="card-tools">
                        <button class="btn btn-tool">
                            <select class="form-control bg-light border-dark" name="id_provinsi" id="tahun">
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == '2024' ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                            </select>
                        </button>
                        {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> --}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="d-md-flex">
                        <div class="p-1 flex-fill" style="overflow: hidden">
                            <!-- Map will be created here -->
                            <div id="world-map-markers" style="height: 525px; overflow: hidden">
                                <div id="legend" style="
                                    position: absolute;
                                    margin-top: 60px;
                                    margin-right: 10px;
                                    top: 10px;
                                    right: 10px;
                                    background: rgba(255,255,255,0.9);
                                    padding: 10px;
                                    border-radius: 5px;
                                    font-size: 12px;
                                    z-index: 1000;
                                    /* visibility: hidden; */
                                    display: none;
                                ">
                                    <div><span style="display:inline-block;width:12px;height:12px;background:green;margin-right:5px;"></span>Sejahtera (C1)</div>
                                    <div><span style="display:inline-block;width:12px;height:12px;background:#ffff00;margin-right:5px;"></span>Menengah (C3)</div>
                                    <div><span style="display:inline-block;width:12px;height:12px;background:red;margin-right:5px;"></span>Kurang Mampu (C2)</div>
                                    <!-- Add more as needed -->
                                </div>
                                <div class="vmap" id="vmap" style="height: 100%" data-toggle="tooltip" data-original-title="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('css')
@endpush

@push('js')
<script type="text/javascript">
// jQuery(document).ready(function() {
//     jQuery('[data-toggle="tooltip"]').tooltip({ container: 'body', placement: 'top', html: true });
//     // reload waktu ubah filter tahun //
//     jQuery("#tahun").on("change", function() {
//         location.reload();
//     });

//     let tahun = jQuery("#tahun").val();

//     jQuery.ajax({
//         // url: "http://localhost/Skripsi/dashboard-clustering/public/api/getDataPeta",
//         url: "/api/getDataPeta",
//         method: "GET",
//         data: { tahun: jQuery("#tahun").val() },
//         success: function(data) {
//             let colors = {};
//             data.forEach(function(item) {
//                 let provinsi = item.path.toLowerCase();
//                 if (item.cluster == 1) {
//                     colors[provinsi] = 'green';
//                 } else if (item.cluster == 2) {
//                     colors[provinsi] = 'red';
//                 } else if (item.cluster == 3) {
//                     colors[provinsi] = 'yellow';
//                 } else {
//                     colors[provinsi] = 'pink'; // Warna default jika tidak ada cluster
//                 }
//             });
//             // console.log('colors', colors);
//             jQuery('#vmap').vectorMap({
//                 map: 'indonesia_id',
//                 backgroundColor: '#a5bfdd',
//                 borderColor: 'black',
//                 borderOpacity: 0.25,
//                 borderWidth: 1,
//                 enableZoom: false,
//                 hoverColor: '#c9dfaf',
//                 normalizeFunction: 'linear',
//                 selectedColor: '#c9dfaf',
//                 showTooltip: false,
//                 colors: colors, // <- Pewarnaan provinsi berdasarkan cluster
//                 // colors: {
//                 //     'path01': '#ff00ff', // Aceh
//                 //     'path02': '#ff0000', // Jakarta
//                 //     'path03': '#00ff00', // Central Java
//                 //     'path04': '#0000ff', // West Java
//                 //     'path05': '#ffff00', // East Java
//                 // },
//                 onRegionClick: function(element, code, region) {
//                     console.log(region);
//                     var tahun = jQuery("#tahun").val();
//                     jQuery.ajax({
//                         // url: "http://localhost/Skripsi/dashboard-clustering/public/api/getDataPeta",
//                         url: "/api/getDataPeta",
//                         method: "GET",
//                         data: { tahun: tahun },
//                         success: function(data) {
//                             let found = data.find(item => item.nama_provinsi.toUpperCase() === region.toUpperCase());
//                             if (found) {
//                                 jQuery("#provinsiName").text(found.nama_provinsi);
//                                 jQuery("#clusterName").text(
//                                     found.cluster == 1 ? "Sejahtera" :
//                                     found.cluster == 2 ? "Kurang Mampu" : "Menengah"
//                                 );
//                                 jQuery("#year").text(found.tahun);
//                                 jQuery("#garisKemiskinan").text(found.garis_kemiskinan);
//                                 jQuery("#upahMinimum").text(found.upah_minimum);
//                                 jQuery("#pengeluaran").text(found.pengeluaran);
//                                 jQuery("#rataRataUpah").text(found.rr_upah);
//                                 jQuery("#modalInfoProvinsi").modal("show");

//                                 if (window.myChart) {
//                                     window.myChart.destroy();
//                                 }

//                                 let ctx = document.getElementById('chartProvinsi').getContext('2d');
//                                 window.myChart = new Chart(ctx, {
//                                     type: 'horizontalBar',
//                                     data: {
//                                         labels: ['Garis Kemiskinan', 'Upah Minimum', 'Pengeluaran', 'Rata-rata Upah'],
//                                         datasets: [{
//                                             data: [
//                                                 found.garis_kemiskinan,
//                                                 found.upah_minimum,
//                                                 found.pengeluaran,
//                                                 found.rr_upah
//                                             ],
//                                             backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#17a2b8']
//                                         }]
//                                     },
//                                     options: {
//                                         responsive: true,
//                                         legend: { display: false },
//                                         scales: { y: { beginAtZero: true, display: true } }
//                                     }
//                                 });
//                             } else {
//                                 alert("Data untuk " + region + " tidak ditemukan.");
//                             }
//                         },
//                         error: function() {
//                             alert("Gagal mengambil data.");
//                         }
//                     });
//                 }
//             });
//             $('#legend').show();
//         },
//         error: function() {
//             alert("Gagal memuat data peta.");
//         }
//     });
    
// });


jQuery(document).ready(function() {
    jQuery('[data-toggle="tooltip"]').tooltip({ container: 'body', placement: 'top', html: true });

    function loadMapData(tahun) {
        jQuery.ajax({
            url: "/api/getDataPeta",
            method: "GET",
            data: { tahun: tahun },
            success: function(data) {
                let colors = {};
                data.forEach(function(item) {
                    let provinsi = item.path.toLowerCase();
                    if (item.cluster == 1) {
                        colors[provinsi] = 'green';
                    } else if (item.cluster == 2) {
                        colors[provinsi] = 'red';
                    } else if (item.cluster == 3) {
                        colors[provinsi] = 'yellow';
                    } else {
                        colors[provinsi] = 'pink'; // Warna default jika tidak ada cluster
                    }
                });

                jQuery('#vmap').empty(); // Bersihkan sebelum render ulang
                jQuery('#vmap').vectorMap({
                    map: 'indonesia_id',
                    backgroundColor: '#a5bfdd',
                    borderColor: 'black',
                    borderOpacity: 0.25,
                    borderWidth: 1,
                    enableZoom: false,
                    hoverColor: '#c9dfaf',
                    normalizeFunction: 'linear',
                    selectedColor: '#c9dfaf',
                    showTooltip: false,
                    colors: colors,
                    onRegionClick: function(element, code, region) {
                        jQuery.ajax({
                            url: "/api/getDataPeta",
                            method: "GET",
                            data: { tahun: tahun },
                            success: function(data) {
                                let found = data.find(item => item.nama_provinsi.toUpperCase() === region.toUpperCase());
                                if (found) {
                                    jQuery("#provinsiName").text(found.nama_provinsi);
                                    jQuery("#clusterName").text(
                                        found.cluster == 1 ? "Sejahtera" :
                                        found.cluster == 2 ? "Kurang Mampu" : "Menengah"
                                    );
                                    jQuery("#year").text(found.tahun);
                                    jQuery("#garisKemiskinan").text(found.garis_kemiskinan);
                                    jQuery("#upahMinimum").text(found.upah_minimum);
                                    jQuery("#pengeluaran").text(found.pengeluaran);
                                    jQuery("#rataRataUpah").text(found.rr_upah);
                                    jQuery("#modalInfoProvinsi").modal("show");

                                    if (window.myChart) {
                                        window.myChart.destroy();
                                    }

                                    let ctx = document.getElementById('chartProvinsi').getContext('2d');
                                    window.myChart = new Chart(ctx, {
                                        type: 'horizontalBar',
                                        data: {
                                            labels: ['Garis Kemiskinan (Rp)', 'Upah Minimum (Rp)', 'Pengeluaran (Rp)', 'Rata-rata Upah per Jam (Rp)'],
                                            datasets: [{
                                                data: [
                                                    found.garis_kemiskinan,
                                                    found.upah_minimum,
                                                    found.pengeluaran,
                                                    found.rr_upah
                                                ],
                                                backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#17a2b8']
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            legend: { display: false },
                                            scales: { y: { beginAtZero: true, display: true } }
                                        }
                                    });
                                } else {
                                    alert("Data untuk " + region + " tidak ditemukan.");
                                }
                            },
                            error: function() {
                                alert("Gagal mengambil data.");
                            }
                        });
                    }
                });

                $('#legend').show();
            },
            error: function() {
                alert("Gagal memuat data peta.");
            }
        });
    }

    // Saat halaman pertama kali dimuat
    let tahunAwal = jQuery("#tahun").val();
    loadMapData(tahunAwal);

    // Saat filter tahun berubah
    jQuery("#tahun").on("change", function() {
        let tahunBaru = jQuery(this).val();
        loadMapData(tahunBaru);
    });
});
</script>
@endpush