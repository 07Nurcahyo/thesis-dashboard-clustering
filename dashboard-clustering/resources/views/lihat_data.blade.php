@extends('layouts_guest.template')

@section('content')

<div class="container">

  <div class="card">
    <div class="card-header d-flex justify-content-center bg-navy">
      <h2 class="card-title font-weight-bold" style="font-size: 22px">Keanggotaan Cluster Akhir Kesejahteraan Pekerja di Indonesia</h2>
    </div>
    <div class="card-body">
      @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="row">
        <div class="col-md-7">
          <div class="form-group row">
            <label class="pl-2 control-label col-form-label font-weight-normal">Filter Provinsi : </label>
            <div class="col-3">
              <select class="form-control" name="id_provinsi_2" id="id_provinsi_2" required>
                <option value="">--Semua--</option>
                @foreach($provinsi as $item)
                  <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-5 text-right">
          <div id="buttons_2" class="btn-group"></div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped w-100" id="tabel_data_cluster">
          <thead>
            <tr style="text-align: center;">
              {{-- <th>No</th> --}}
              <th>ID</th>
              <th>Provinsi</th>
              <th>Kategori</th>
              <th>Tahun</th>
              <th>Garis Kemiskinan</th>
              <th>Upah Minimum</th>
              <th>Pengeluaran</th>
              <th>Rata-rata Upah</th>
            </tr>
          </thead>
        </table>
      </div>

    </div> <!-- /.card-body -->
  </div>


  <div class="card">
    <div class="card-header d-flex justify-content-center bg-navy">
      <h2 class="card-title font-weight-bold" style="font-size: 22px">Iterasi Akhir</h2>
    </div>
    <div class="card-body">
      @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="row">
        <div class="col-md-7">
          <div class="form-group row">
            <label class="pl-2 control-label col-form-label font-weight-normal">Filter Provinsi : </label>
            <div class="col-3">
              <select class="form-control" name="id_provinsi_3" id="id_provinsi_3" required>
                <option value="">--Semua--</option>
                @foreach($provinsi as $item)
                  <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-5 text-right">
          <div id="buttons_3" class="btn-group"></div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped w-100" id="tabel_data_iterasi">
          <thead>
            <tr style="text-align: center;">
              <th>No</th>
              <th>Provinsi</th>
              <th>Tahun</th>
              <th>Jarak C1</th>
              <th>Jarak C2</th>
              <th>Jarak C3</th>
              <th>C Terdekat</th>
              <th>Cluster</th>
              <th>Jarak Minimum</th>
            </tr>
          </thead>
        </table>
      </div>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header d-flex justify-content-center bg-navy">
          <h2 class="card-title font-weight-bold" style="font-size: 22px">SSE Iterasi Akhir</h2>
        </div>
        <div class="card-body">
          {{-- <div class="card-title font-weight-bold">Berikut adalah SSE dari iterasi akhir : </div> --}}
          <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="tabel_data_sse">
              <thead>
                <th>SSE</th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header d-flex justify-content-center bg-navy">
          <h2 class="card-title font-weight-bold" style="font-size: 22px">Centroid Akhir</h2>
        </div>
        <div class="card-body">
          {{-- <div class="card-title font-weight-bold">Berikut adalah data centroid akhir : </div> --}}
          <div class="table-responsive">
            <table class="table table-bordered table-striped w-auto" id="tabel_data_cluster_akhir">
              <thead>
                <th>Cluster</th>
                <th>Garis Kemiskinan</th>
                <th>Upah Minimum</th>
                <th>Pengeluaran</th>
                <th>Rata-rata Upah</th>
                <th>Kategori</th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div><!-- /.container-fluid -->

@endsection

@push('css')
@endpush

@push('js')
<script>
  $(document).ready(function() {

    var dataPekerjaCluster = $('#tabel_data_cluster').DataTable({
      serverSide: false,
      ajax: {
        url: "{{ url('/lihat_data/list') }}",
        dataType: "json",
        type: "GET",
        data: function(d) {
          d.id_provinsi = $('#id_provinsi_2').val();
        }
      },
      columns: [
        // {
        //   data: "DT_RowIndex",
        //   className: "text-center",
        //   orderable: false,
        //   searchable: true
        // },
        {
          data: "id",
          className: "text-center",
          orderable: false,
          searchable: true
        },
        {
          data: "provinsi.nama_provinsi",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "cluster.nama_cluster",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "tahun",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "garis_kemiskinan",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          
          data: "upah_minimum",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "pengeluaran",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "rr_upah",
          className: "text-center",
          orderable: true,
          searchable: true
        },
      ],
      buttons: [
        {
          extend: 'copyHtml5',
          text: '<i class="fas fa-copy"></i>',
          titleAttr: 'Copy',
          className: 'btn btn-default btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i>',
          titleAttr: 'Excel',
          className: 'btn btn-success btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fas fa-file-pdf"></i>',
          titleAttr: 'PDF',
          title: 'Data Cluster Kesejahteraan Pekerja di Indonesia',
          className: 'btn btn-danger btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i>',
          titleAttr: 'Print',
          title: 'Data Cluster Kesejahteraan Pekerja di Indonesia',
          className: 'btn btn-info btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "order": [[ 1, "asc" ], [ 3, "asc" ]],
      initComplete: function() {
        var api = this.api();
        api.buttons().container().appendTo('#buttons_2');// .addClass('float-right');
        // $('#buttons').html(dataPekerja.buttons().container().html());
      },
    });
    $('#id_provinsi_2').on('change',function() {
      dataPekerjaCluster.ajax.reload();
    });
    $('#buttons_2').html(dataPekerjaCluster.buttons().container());


    var dataPekerjaCluster = $('#tabel_data_iterasi').DataTable({
      serverSide: false,
      ajax: {
        url: "{{ url('/lihat_data/list_data_iterasi_default') }}",
        dataType: "json",
        type: "GET",
        data: function(d) {
          d.id_provinsi = $('#id_provinsi_3').val();
        }
      },
      columns: [
        {
          data: "DT_RowIndex",
          // data: "id",
          className: "text-center",
          orderable: false,
          searchable: true
        },
        {
          data: "provinsi.nama_provinsi",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "tahun",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "jarak_c1",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          
          data: "jarak_c2",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "jarak_c3",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "c_terkecil",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "cluster.cluster",
          className: "text-center",
          orderable: true,
          searchable: true
        },
        {
          data: "jarak_minimum",
          className: "text-center",
          orderable: true,
          searchable: true
        },
      ],
      buttons: [
        {
          extend: 'copyHtml5',
          text: '<i class="fas fa-copy"></i>',
          titleAttr: 'Copy',
          className: 'btn btn-default btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i>',
          titleAttr: 'Excel',
          className: 'btn btn-success btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fas fa-file-pdf"></i>',
          titleAttr: 'PDF',
          title: 'Data Iterasi Akhir',
          className: 'btn btn-danger btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i>',
          titleAttr: 'Print',
          title: 'Data Iterasi Akhir',
          className: 'btn btn-info btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      initComplete: function() {
        var api = this.api();
        api.buttons().container().appendTo('#buttons_3');// .addClass('float-right');
        // $('#buttons').html(dataPekerja.buttons().container().html());
      },
    });
    $('#id_provinsi_3').on('change',function() {
      dataPekerjaCluster.ajax.reload();
    });
    $('#buttons_3').html(dataPekerjaCluster.buttons().container());

    var dataPekerjaCluster = $('#tabel_data_sse').DataTable({
      serverSide: false,
      ajax: {
        url: "{{ url('/lihat_data/list_data_sse') }}",
        dataType: "json",
        type: "GET"
      },
      columns: [
        {
          data: "sse",
          className: "text-center"
        }
      ],
      dom: 't', // Hides the "show ... entries", search, and pagination controls
    });

    var dataPekerjaCluster = $('#tabel_data_cluster_akhir').DataTable({
      serverSide: false,
      ajax: {
        url: "{{ url('/lihat_data/list_data_cluster_akhir') }}",
        dataType: "json",
        type: "GET"
      },
      columns: [
        {
          data: "cluster.cluster",
          className: "text-center"
        },
        {
          data: "garis_kemiskinan",
          className: "text-center"
        },
        {
          data: "upah_minimum",
          className: "text-center"
        },
        {
          data: "pengeluaran",
          className: "text-center"
        },
        {
          data: "rr_upah",
          className: "text-center"
        },
        {
          data: "cluster.nama_cluster",
          className: "text-center"
        },
      ],
      dom: 't', // Hides the "show ... entries", search, and pagination controls
    });
  });
</script>
@endpush