@extends('layouts_guest.template')

@section('content')
    {{-- <div class="container">
      <div class="row">
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid --> --}}
    <div class="card">
        <div class="card-header d-flex justify-content-center bg-info">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Barchart Data Kesejahteraan Pekerja Indonesia</h2>
        </div>
        <div class="card-body">

            <select class="form-control my-3" name="tahun" id="tahun" required>
              {{-- <option value="">--Pilih Tahun--</option> --}}
              @foreach($tahunList as $tahun)
                <option value="{{ $tahun }}" {{ $tahun == '2024' ? 'selected' : '' }}>{{ $tahun }}</option>
              @endforeach
            </select>
            
            <div class="row" id="dataChart">
                <div class="chart d-flex col-md-6 justify-content-center align-items-center" style="height: 750px;">
                  <canvas id="garisKemiskinanChart"></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
                <div class="chart col-md-6 d-flex justify-content-center align-items-center">
                  <canvas id="upahMinimumChart"></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
            </div>
            <div class="row">
                <div class="chart d-flex col-md-6 justify-content-center align-items-center" style="height: 400px;">
                  <canvas id="pengeluaranChart"></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
                <div class="chart col-md-6 d-flex justify-content-center align-items-center">
                  <canvas id="rataRataUpahChart"></canvas>
                    {{-- <p>Tes</p> --}}
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
            type: 'horizontalBar',
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
                }
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
});
</script>
@endpush