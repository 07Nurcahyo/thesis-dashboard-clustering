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
            {{-- <select class="form-control" name="id_provinsi" id="id_provinsi" required>
              <option value="">--Semua--</option>
              @foreach($provinsi as $item)
                <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
              @endforeach
            </select> --}}
            <div class="row" id="dataChart">
                <div class="chart d-flex col-md-6 justify-content-center align-items-center" style="height: 5000px">
                    <canvas id="garisKemiskinanChart dataChart" style=""></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
                <div class="chart col-md-6 d-flex justify-content-center align-items-center">
                    <canvas id="upahMinimumChart dataChart" style=""></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
            </div>
            <div class="row">
                <div class="chart d-flex col-md-6 justify-content-center align-items-center">
                    <canvas id="pengeluaranChart dataChart" style=""></canvas>
                    {{-- <p>Tes</p> --}}
                </div>
                <div class="chart col-md-6 d-flex justify-content-center align-items-center">
                    <canvas id="rataRataUpahChart dataChart" style=""></canvas>
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
  // Filter
  // $document.ready(function() {
  //   var dataPekerja = $('#dataChart').DataTable({
  //     serverSide: false,
  //       ajax: {
  //         // url: "{{ url('/lihat_data/list') }}",
  //         dataType: "json",
  //         type: "GET",
  //         data: function(d) {
  //           d.id_provinsi = $('#id_provinsi').val();
  //         }
  //       }
  //   });
  // })

  // Chart
  $(function () {
    // fungsi garis kemiskinan
    const ctx = document.getElementById('garisKemiskinanChart').getContext('2d');
    const apiURL = 'http://localhost/Skripsi/dashboard-clustering/public/api/getDataGK';
    async function fetchDataGK() {
      try {
        const response = await fetch(apiURL);
        const data = await response.json();
        return data;
      } catch (error) {
        console.error('Error fetching data:', error);
        return null;
      }
    }
    async function createDataGKChart() {
      const data = await fetchDataGK();
      if (data) {
        const labels = data.map(item => item.nama_provinsi);
        const values = data.map(item => item.garis_kemiskinan);
        const chartData = {
          labels: labels,
          datasets: [{
            label: 'Garis Kemiskinan',
            data: values,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        };
        new Chart(ctx, {
          type: 'horizontalBar',
          data: chartData,
          options: {
            // auto height
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Garis Kemiskinan'
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
    }
    createDataGKChart();
  })
</script>
@endpush