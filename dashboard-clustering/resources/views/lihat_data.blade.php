@extends('layouts_guest.template')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-center bg-info">
      <h2 class="card-title font-weight-bold" style="font-size: 22px">Data Kesejahteraan Pekerja di Indonesia</h2>
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
              <select class="form-control" name="id_provinsi" id="id_provinsi" required>
                <option value="">--Semua--</option>
                @foreach($provinsi as $item)
                  <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-5 text-right">
          <div id="buttons" class="btn-group"></div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped w-100" id="tabel_data">
          <thead>
            <tr style="text-align: center;">
              <th>No</th>
              <th>Nama Provinsi</th> {{-- fk id_provinsi -> nama provinsi --}}
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
</div><!-- /.container-fluid -->
@endsection

@push('css')
@endpush

@push('js')
<script>
  $(document).ready(function() {
    var dataPekerja = $('#tabel_data').DataTable({
      serverSide: false,
      ajax: {
        url: "{{ url('/lihat_data/list') }}",
        dataType: "json",
        type: "GET",
        data: function(d) {
          d.id_provinsi = $('#id_provinsi').val();
        }
      },
      columns: [
        {
          // data: "DT_RowIndex",
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
          className: 'btn btn-danger btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i>',
          titleAttr: 'Print',
          className: 'btn btn-info btn-sm',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      initComplete: function() {
        var api = this.api();
        api.buttons().container().appendTo('#buttons');// .addClass('float-right');
        // $('#buttons').html(dataPekerja.buttons().container().html());
      },
    });
    $('#id_provinsi').on('change',function() {
      // dataPekerja.ajax.reload(null, false);
      dataPekerja.ajax.reload();
    });
    $('#buttons').html(dataPekerja.buttons().container());

    // sweetalert2
  });
</script>
@endpush