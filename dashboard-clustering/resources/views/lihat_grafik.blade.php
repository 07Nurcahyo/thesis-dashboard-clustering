@extends('layouts_guest.template')

@section('content')
<div class="container">
    {{-- barchart --}}
    <div class="card">
        <div class="card-header bg-info">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Barchart Data Kesejahteraan Pekerja Indonesia</h2>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus" style="color: white;"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <select class="form-control my-3" name="id_provinsi" id="tahun" required>
              {{-- <option value="">--Pilih Tahun--</option> --}}
              @foreach($tahunList as $tahun)
                <option value="{{ $tahun }}" {{ $tahun == '2024' ? 'selected' : '' }}>{{ $tahun }}</option>
              @endforeach
            </select>
            {{-- <div class="chart col-md-6 d-flex justify-content-center align-items-center"> --}}
            
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
    </div><br>

    {{-- linechart --}}
    <div class="card">
        <div class="card-header bg-info">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Linechart Data Kesejahteraan Pekerja Indonesia</h2>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus" style="color: white;"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <select class="form-control my-3" name="provinsi" id="provinsi">
                @foreach($provinsiList as $provinsi=>$value)
                  {{-- <option value="{{ $provinsi->id_provinsi }}">{{ $provinsi->nama_provinsi }}</option> --}}
                  <option value="{{ $provinsi }}">{{ $value }}</option>
                @endforeach
            </select>
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartGK"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartUMP"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartPengeluaran"></canvas>
            </div><hr>
            <div class="chart" style="height: 400px;">
                <canvas id="lineChartRRU"></canvas>
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
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataGK',
                labelKey: 'garis_kemiskinan',
                displayLabel: 'Garis Kemiskinan',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)'
            },
            {
                canvasId: 'upahMinimumChart',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataUMP',
                labelKey: 'upah_minimum',
                displayLabel: 'Upah Minimum',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)'
            },
            {
                canvasId: 'pengeluaranChart',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataPengeluaran',
                labelKey: 'pengeluaran',
                displayLabel: 'Pengeluaran',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)'
            },
            {
                canvasId: 'rataRataUpahChart',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataRRU',
                labelKey: 'rr_upah',
                displayLabel: 'Rata-Rata Upah',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)'
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
                }
            }
        });
    }
    async function renderAllLineCharts(provinsi) {
        const chartConfigs = [
            {
                canvasId: 'lineChartGK',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getLineGK',
                labelKey: 'garis_kemiskinan',
                displayLabel: 'Garis Kemiskinan',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)'
            },
            {
                canvasId: 'lineChartUMP',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getLineUMP',
                labelKey: 'upah_minimum',
                displayLabel: 'Upah Minimum',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)'
            },
            {
                canvasId: 'lineChartPengeluaran',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getLinePengeluaran',
                labelKey: 'pengeluaran',
                displayLabel: 'Pengeluaran',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)'
            },
            {
                canvasId: 'lineChartRRU',
                apiUrl: 'http://localhost/Skripsi/dashboard-clustering/public/api/getLineRRU',
                labelKey: 'rr_upah',
                displayLabel: 'Rata-Rata Upah',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)'
            }
        ];

        for (const config of chartConfigs) {
            const data = await fetchLineChartData(config.apiUrl, provinsi);
            renderLineChart({ ...config, data });
        }
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