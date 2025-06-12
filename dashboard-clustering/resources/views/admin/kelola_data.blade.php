@extends('layouts.template')

@section('content')
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Data Kesejahteraan Pekerja di Indonesia</h2>
            <div class="card-tools">
                <button class="btn btn-success m-0 p-0 mr-3 p-1" data-toggle="modal" data-target="#jumlahInputModal">
                    <i class="fas fa-plus"></i>  Tambah Data Manual
                </button>
                <button class="btn btn-success m-0 p-0 p-1" data-toggle="modal" data-target="#uploadCsvModal">
                    <i class="fas fa-file"></i>  Tambah Data CSV
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
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

            <table class="table table-bordered table-striped w-100 table-sm" id="tabel_data_pekerja">
                <thead>
                    <tr style="text-align: center;">
                    {{-- <th>No</th> --}}
                    <th>No</th>
                    <th>Provinsi</th>
                    <th>Tahun</th>
                    <th>Garis Kemiskinan</th>
                    <th>Upah Minimum</th>
                    <th>Pengeluaran</th>
                    <th>Rata-rata Upah</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
            </table>

        </div> <!-- /.card-body -->
        <!-- Modal Jumlah Input -->
        <div class="modal fade" id="jumlahInputModal" tabindex="-1" role="dialog" aria-labelledby="jumlahInputModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <form id="jumlahForm">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title">Masukkan Jumlah Data</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="number" min="1" class="form-control" id="jumlah_data" placeholder="Jumlah data yang ingin dimasukkan">
                        <button type="submit" class="btn btn-warning mt-3">Lanjut   <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- Modal Form Data Manual -->
        <div class="modal fade" id="formInputModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg " role="document">
                <form id="manualForm" class="boder-dark">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title">Input Data Manual</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" id="formInputContainer" style="overflow-y: scroll; max-height: 450px;">
                        <!-- Dynamic Forms Generated Here -->
                    </div>
                        <div class="modal-footer bg-secondary">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"> </i>  Simpan</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- Modal Upload CSV -->
        <div class="modal fade" id="uploadCsvModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <form id="csvUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title">Upload CSV</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" style="overflow-y: scroll; max-height: 450px;">
                        <p class="badge badge-warning" style="font-size: 18px">Silakan pilih file CSV atau Excel untuk diunggah. Pastikan formatnya sesuai dengan yang diharapkan.</p><br>
                        <p class="badge badge-warning" style="font-size: 18px">Centang untuk memilih data yang akan dimasukkan</p><br>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="badge badge-warning" style="font-size: 18px">Unduh file template berikut:</p>
                            </div>
                            <div class="col-md-9">
                                {{-- <a href="{{ asset('template/template_data_kesejahteraan.xlsx') }}" class="btn btn-sm btn-outline-primary mb-3" download>
                                    <i class="fas fa-file"></i> Unduh Template Excel
                                </a> --}}
                                <a href="{{ url('admin/download-template-excel') }}" class="btn btn-sm btn-outline-primary mb-3"><i class="fas fa-file"></i> Download Template Excel</a>
                            </div>
                        </div>
                        {{-- <p class="badge badge-warning" style="font-size: 18px">
                            Unduh file template berikut: 
                            <a href="{{ asset('template/template_data_pekerja.xlsx') }}" download>Download Template Excel</a>
                        </p> --}}
                        <input type="file" name="csv_file" id="csv_file" accept=".csv,.xlsx" class="form-control mb-3" required>
                        <div id="csv_preview_container"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Data</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- Modal Edit Data -->
        <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="editForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-navy">
                            <h5 class="modal-title">Edit Data Pekerja</h5>
                            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <select name="id_provinsi" id="edit_id_provinsi" class="form-control" required>
                                            @foreach($provinsi as $item)
                                                <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" name="tahun" id="edit_tahun" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Garis Kemiskinan</label>
                                        <input type="number" name="garis_kemiskinan" id="edit_garis_kemiskinan" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Upah Minimum</label>
                                        <input type="number" name="upah_minimum" id="edit_upah_minimum" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pengeluaran</label>
                                        <input type="number" name="pengeluaran" id="edit_pengeluaran" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rata-rata Upah</label>
                                        <input type="number" name="rr_upah" id="edit_rr_upah" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>  Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
            
        var dataPekerja = $('#tabel_data_pekerja').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_data_pekerja') }}",
                dataType: "json",
                type: "GET",
                data: function (d) {
                    d.id_provinsi = $('#id_provinsi').val();
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
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
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
                    title: 'Data Kesejahteraan Pekerja di Indonesia',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i>',
                    titleAttr: 'Print',
                    title: 'Data Kesejahteraan Pekerja di Indonesia',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 1, "asc" ], [ 2, "asc" ]],
            initComplete: function() {
                var api = this.api();
                api.buttons().container().appendTo('#buttons');
            },
        });
        $('#id_provinsi').on('change',function() {
            dataPekerja.ajax.reload();
        });
        $('#buttons').html(dataPekerja.buttons().container());
        
        // input data manual
        $('#jumlahForm').on('submit', function(e) {
            e.preventDefault();
            const jumlah = parseInt($('#jumlah_data').val());
            let formHTML = '';
            for (let i = 0; i < jumlah; i++) {
                formHTML += `
                <div class="border p-3 mb-3 border-dark">
                    <h3 class="text-bold" style="text-decoration: underline">Data #${i + 1}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <select name="data[${i}][id_provinsi]" class="form-control" required>
                                    @foreach($provinsi as $item)
                                        <option value="{{$item->id_provinsi}}">{{$item->nama_provinsi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="number" name="data[${i}][tahun]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Garis Kemiskinan</label>
                                <input type="number" name="data[${i}][garis_kemiskinan]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upah Minimum</label>
                                <input type="number" name="data[${i}][upah_minimum]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pengeluaran</label>
                                <input type="number" name="data[${i}][pengeluaran]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rata-rata Upah</label>
                                <input type="number" name="data[${i}][rr_upah]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            $('#formInputContainer').html(formHTML);
            $('#jumlahInputModal').modal('hide');
            $('#formInputModal').modal('show');
        });
        $('#manualForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('admin/store_data_pekerja') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#formInputModal').modal('hide');
                    $('#tabel_data_pekerja').DataTable().ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                        showConfirmButton: true
                    });
                }
            });
        });


        // input csv
        // $('#csv_file').on('change', function () {
        //     let file = this.files[0];
        //     if (!file) return;
        //     let formData = new FormData();
        //     formData.append('csv_file', file);
        //     formData.append('_token', '{{ csrf_token() }}');
        //     $.ajax({
        //         url: "{{ url('admin/preview_csv') }}",
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function (res) {
        //             let tableHtml = `<table class="table table-bordered"><thead><tr>`;
        //                     tableHtml += `<th><input type="checkbox" id="select_all" checked/></th>`; // Header untuk checkbox
        //                     ['Provinsi', 'Tahun', 'Garis Kemiskinan', 'Upah Minimum', 'Pengeluaran', 'Rata-rata Upah'].forEach((col, index) => {
        //                         tableHtml += `<th>${col}</th>`;
        //                     });
        //             tableHtml += `</tr></thead><tbody>`;
        //             res.rows.forEach((row, index) => {
        //                 tableHtml += `<tr>`;
        //                 tableHtml += `<td><input type="checkbox" name="row_ids[]" value="${index}" checked/></td>`; // Checkbox sebagai kolom pertama
        //                 row.forEach(cell => tableHtml += `<td>${cell}</td>`);
        //                 tableHtml += `</tr>`;
        //             });
        //             // dicentang = include data;
        //             // data yang dimasukkan hanya yang ada di header
        //             tableHtml += `</tbody></table>`;
        //             $('#csv_preview_container').html(tableHtml);

        //             // Optional: fungsi untuk checkbox "select all"
        //             $('#select_all').on('change', function () {
        //                 $('input[name="row_ids[]"]').prop('checked', this.checked);
        //             });
        //         }
        //     });
        // });
        // input csv
        $('#csv_file').on('change', function () {
            let file = this.files[0];
            if (!file) return;
            let formData = new FormData();
            formData.append('csv_file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: "{{ url('admin/preview_csv') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    let tableHtml = `<table class="table table-bordered"><thead><tr>`;
                    tableHtml += `<th><input type="checkbox" id="select_all" checked/></th>`; // Header untuk checkbox
                    ['Provinsi', 'Tahun', 'Garis Kemiskinan', 'Upah Minimum', 'Pengeluaran', 'Rata-rata Upah'].forEach((col, index) => {
                        tableHtml += `<th>${col}</th>`;
                    });
                    tableHtml += `</tr></thead><tbody>`;

                    // Mulai dari index 1 supaya skip header dari file
                    for(let index = 1; index < res.rows.length; index++) {
                        let row = res.rows[index];
                        tableHtml += `<tr>`;
                        tableHtml += `<td><input type="checkbox" name="row_ids[]" value="${index}" checked/></td>`; // Checkbox sebagai kolom pertama
                        row.forEach(cell => tableHtml += `<td>${cell}</td>`);
                        tableHtml += `</tr>`;
                    }

                    tableHtml += `</tbody></table>`;
                    $('#csv_preview_container').html(tableHtml);

                    // Checkbox "select all"
                    $('#select_all').on('change', function () {
                        $('input[name="row_ids[]"]').prop('checked', this.checked);
                    });
                }
            });
        });

        $('#csvUploadForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
    
            $.ajax({
                url: "{{ url('admin/import_csv') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function () {
                    $('#uploadCsvModal').modal('hide');
                    $('#tabel_data_pekerja').DataTable().ajax.reload();
                    // alert('Data berhasil diimport');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Diimport!',
                        text: 'Data Kesejahteraan Pekerja Di Indonesia Berhasil Diimport.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        deleteConfirm = function(id) {
            event.preventDefault(); // mencegah form submit
            console.log("Data ID: "+id);
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data dengan ID " +id+ " akan dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        // url: 'http://localhost/Skripsi/dashboard-clustering/public/api/deleteDataPekerja/' + id,
                        url: `/api/deleteDataPekerja/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success == true) {
                                Swal.fire({
                                    title: "Terhapus!",
                                    text: "Data pekerja telah terhapus!",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    dataPekerja.ajax.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal Terhapus!",
                                    text: response.error,
                                    // icon: "error"
                                    imageUrl: "https://i.gifer.com/XwI7.gif",
                                });
                            }
                        }
                    });
                }
            });
        }
    });

    // ketika tombol edit diklik
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.get(`/admin/${id}/edit-json`, function(res) {
            $('#edit_id').val(res.id);
            $('#edit_id_provinsi').val(res.id_provinsi);
            $('#edit_tahun').val(res.tahun);
            $('#edit_garis_kemiskinan').val(res.garis_kemiskinan);
            $('#edit_upah_minimum').val(res.upah_minimum);
            $('#edit_pengeluaran').val(res.pengeluaran);
            $('#edit_rr_upah').val(res.rr_upah);
            $('#editDataModal').modal('show');
        });
    });
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        $.ajax({
            url: `/admin/${id}`,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function(res) {
                $('#editDataModal').modal('hide');
                $('#tabel_data_pekerja').DataTable().ajax.reload();
                // alert('Data berhasil diperbarui!');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diperbarui.',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function() {
                // alert('Terjadi kesalahan saat mengedit data.');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengubah data.',
                    showConfirmButton: true
                });
            }
        });
    });

</script>
@endpush