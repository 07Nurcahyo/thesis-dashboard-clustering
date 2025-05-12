@extends('layouts.template')

@section('content')
<div class="container">
    {{-- linechart --}}
    <div class="card">
        <div class="card-header bg-secondary">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Linechart Data Kesejahteraan Pekerja Indonesia</h2>
            <div class="card-tools">
                <button class="btn btn-tool">
                    <select class="form-control bg-light border-dark" name="provinsi" id="provinsi">
                        @foreach($provinsiList as $provinsi=>$value)
                          {{-- <option value="{{ $provinsi->id_provinsi }}">{{ $provinsi->nama_provinsi }}</option> --}}
                          <option value="{{ $provinsi }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus" style="color: white;"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="overflow-y: scroll; max-height: 450px;">
            
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartCombined"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartRRU"></canvas>
            </div>

        </div>

    </div>

    {{-- barchart --}}
    <div class="card">
        <div class="card-header bg-secondary">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Barchart Data Kesejahteraan Pekerja Indonesia</h2>
            <div class="card-tools">
                <button class="btn btn-tool">
                    <select class="form-control bg-light border-dark" name="id_provinsi" id="tahun">
                      @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == '2024' ? 'selected' : '' }}>{{ $tahun }}</option>
                      @endforeach
                    </select>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus" style="color: white;"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="overflow-y: scroll; max-height: 450px;">
            <div class="chart" style="height: 400px;">
                {{-- <p class="font-weight-bold text-center">Garis Kemiskinan</p> --}}
                <canvas id="garisKemiskinanChart"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                {{-- <p class="font-weight-bold text-center">Upah Minimum</p> --}}
                <canvas id="upahMinimumChart"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                {{-- <p class="font-weight-bold text-center">Pengeluaran</p> --}}
                <canvas id="pengeluaranChart"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                {{-- <p class="font-weight-bold text-center">Rata-Rata Upah</p> --}}
                <canvas id="rataRataUpahChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
<script>
$(function () {
    const chartInstances = {}; // Menyimpan semua chart agar bisa di-destroy

    // Fungsi fetch data dari API
    async function fetchChartData(apiUrl, tahun) {
        try {
            const response = await fetch(`${apiUrl}?tahun=${tahun}`);
            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
            return [];
        }
    }
    // Fungsi membuat dan menampilkan chart
    function renderChart({ canvasId, labelKey, displayLabel, data, backgroundColor, borderColor }) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        const labels = data.map(item => item.nama_provinsi);
        const values = data.map(item => item[labelKey]);
        if (chartInstances[canvasId]) {
            chartInstances[canvasId].destroy();
        }
        chartInstances[canvasId] = new Chart(ctx, {
            // type: 'horizontalBar',
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: displayLabel,
                    data: values,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: displayLabel
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Provinsi'
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        fontColor: '',
                        fontSize: 14,
                        fontStyle: 'bold'
                    }
                },
            }
        });
    }


    // Fungsi utama untuk render semua chart
    async function renderAllCharts(tahun) {
      const chartConfigs = [
            {
                canvasId: 'garisKemiskinanChart',
                // apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataGK',
                apiUrl: '/api/getDataGK',
                labelKey: 'garis_kemiskinan',
                displayLabel: 'Garis Kemiskinan',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)'
            },
            {
                canvasId: 'upahMinimumChart',
                // apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataUMP',
                apiUrl: '/api/getDataUMP',
                labelKey: 'upah_minimum',
                displayLabel: 'Upah Minimum',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)'
            },
            {
                canvasId: 'pengeluaranChart',
                // apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataPengeluaran',
                apiUrl: '/api/getDataPengeluaran',
                labelKey: 'pengeluaran',
                displayLabel: 'Pengeluaran',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)'
            },
            {
                canvasId: 'rataRataUpahChart',
                // apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataRRU',
                apiUrl: '/api/getDataRRU',
                labelKey: 'rr_upah',
                displayLabel: 'Rata-Rata Upah',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)'
            }
        ];
        for (const config of chartConfigs) {
            const data = await fetchChartData(config.apiUrl, tahun);
            renderChart({ ...config, data });
        }
    }
    // Event ketika tahun dipilih
    $('#tahun').on('change', function () {
        const tahun = $(this).val();
        if (tahun) {
            renderAllCharts(tahun);
        }
    });
    // Jika ada default tahun terpilih saat load
    const initialTahun = $('#tahun').val();
    if (initialTahun) {
        renderAllCharts(initialTahun);
    }




    // line chart
    async function fetchLineChartData(apiUrl, provinsi) {
        try {
            const response = await fetch(`${apiUrl}?id_provinsi=${provinsi}`);
            return await response.json(); // diasumsikan hasilnya array objek dengan tahun dan nilai
        } catch (error) {
            console.error('Fetch error:', error);
            return [];
        }
    }
    function renderLineChart({ canvasId, labelKey, displayLabel, data, backgroundColor, borderColor }) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        const labels = data.map(item => item.tahun);
        const values = data.map(item => item[labelKey]);
        if (chartInstances[canvasId]) {
            chartInstances[canvasId].destroy();
        }
        chartInstances[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: displayLabel,
                    data: values,
                    fill: false,
                    borderColor: borderColor,
                    backgroundColor: backgroundColor,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: displayLabel
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        fontColor: 'black',
                        fontSize: 14,
                        fontStyle: 'bold'
                    }
                },
            }
        });
    }
    // line chart gabungan
    // async function renderAllLineCharts(provinsi) {
    //     const ctx = document.getElementById('lineChartCombined').getContext('2d');
    //     if (chartInstances['lineChartCombined']) {
    //         chartInstances['lineChartCombined'].destroy();
    //     }
    //     const apis = {
    //         'Garis Kemiskinan': {
    //             api: '/api/getLineGK',
    //             key: 'garis_kemiskinan',
    //             color: 'rgba(255, 99, 132, 1)'
    //         },
    //         'Upah Minimum': {
    //             api: '/api/getLineUMP',
    //             key: 'upah_minimum',
    //             color: 'rgba(255, 206, 86, 1)'
    //         },
    //         'Pengeluaran': {
    //             api: '/api/getLinePengeluaran',
    //             key: 'pengeluaran',
    //             color: 'rgba(153, 102, 255, 1)'
    //         },
    //         'Rata-Rata Upah per Jam': {
    //             api: '/api/getLineRRU',
    //             key: 'rr_upah',
    //             color: 'rgba(75, 192, 192, 1)'
    //         }
    //     };

    //     const datasets = [];
    //     let labels = [];
    //     for (const [label, config] of Object.entries(apis)) {
    //         const data = await fetchLineChartData(config.api, provinsi);
    //         if (labels.length === 0) {
    //             labels = data.map(item => item.tahun);
    //         }
    //         datasets.push({
    //             label: label,
    //             data: data.map(item => item[config.key]),
    //             borderColor: config.color,
    //             fill: false,
    //             tension: 0.1
    //         });
    //     }
    //     chartInstances['lineChartCombined'] = new Chart(ctx, {
    //         type: 'line',
    //         data: {
    //             labels: labels,
    //             datasets: datasets
    //         },
    //         options: {
    //             responsive: true,
    //             maintainAspectRatio: false,
    //             scales: {
    //                 x: {
    //                     title: {
    //                         display: true,
    //                         text: 'Tahun'
    //                     }
    //                 },
    //                 y: {
    //                     title: {
    //                         display: true,
    //                         text: 'Nilai'
    //                         }
    //                     }
    //                 },
    //                 plugins: {
    //                     legend: {
    //                         display: true,
    //                         position: 'top',
    //                         labels: {
    //                             font: {
    //                                 size: 14,
    //                                 weight: 'bold'
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //     });
    // }

    async function renderAllLineCharts(provinsi) {
        // Hancurkan jika ada chart lama
        if (chartInstances['lineChartCombined']) {
            chartInstances['lineChartCombined'].destroy();
        }
        if (chartInstances['lineChartRRU']) {
            chartInstances['lineChartRRU'].destroy();
        }

        // Combined chart: GK, UMP, Pengeluaran
        const combinedApis = {
            'Garis Kemiskinan': {
                api: '/api/getLineGK',
                key: 'garis_kemiskinan',
                color: 'rgba(255, 99, 132, 1)'
            },
            'Upah Minimum': {
                api: '/api/getLineUMP',
                key: 'upah_minimum',
                color: 'rgba(255, 206, 86, 1)'
            },
            'Pengeluaran': {
                api: '/api/getLinePengeluaran',
                key: 'pengeluaran',
                color: 'rgba(153, 102, 255, 1)'
            }
        };
        const combinedDatasets = [];
        let labels = [];
        for (const [label, config] of Object.entries(combinedApis)) {
            const data = await fetchLineChartData(config.api, provinsi);
            if (labels.length === 0) {
                labels = data.map(item => item.tahun);
            }
            combinedDatasets.push({
                label: label,
                data: data.map(item => item[config.key]),
                borderColor: config.color,
                fill: false,
                tension: 0.1
            });
        }
        const ctxCombined = document.getElementById('lineChartCombined').getContext('2d');
        chartInstances['lineChartCombined'] = new Chart(ctxCombined, {
            type: 'line',
            data: {
                labels: labels,
                datasets: combinedDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Tahun' }
                    },
                    y: {
                        title: { display: true, text: 'Nilai' }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { size: 14, weight: 'bold' } }
                    }
                }
            }
        });
        // Rata-Rata Upah chart sendiri
        const rruData = await fetchLineChartData('/api/getLineRRU', provinsi);
        const ctxRRU = document.getElementById('lineChartRRU').getContext('2d');
        chartInstances['lineChartRRU'] = new Chart(ctxRRU, {
            type: 'line',
            data: {
                labels: rruData.map(item => item.tahun),
                datasets: [{
                    label: 'Rata-Rata Upah',
                    data: rruData.map(item => item.rr_upah),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Tahun' }
                    },
                    y: {
                        title: { display: true, text: 'Rata-Rata Upah' }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { size: 14, weight: 'bold' } }
                    }
                }
            }
        });
    }


    $('#provinsi').on('change', function () {
        const provinsi = $(this).val();
        console.log(provinsi);
        if (provinsi) {
            renderAllLineCharts(provinsi);
        }
    });

    const initialProvinsi = $('#provinsi').val();
    if (initialProvinsi) {
        renderAllLineCharts(initialProvinsi);
    }

});
</script>
@endpush