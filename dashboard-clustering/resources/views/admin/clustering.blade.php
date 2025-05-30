@extends('layouts.template')

@section('content')

    {{-- Preview data --}}
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Preview Data Kesejahteraan Pekerja di Indonesia</h2>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
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
                    </tr>
                </thead>
            </table>
        </div> <!-- /.card-body -->
    </div>

    {{-- Centroid awal --}}
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Centroid Awal</h2>
            <div class="card-tools">
                <button type="button" class="btn m-0 p-0 mr-3 p-1 btn-warning" id="btnAcak">
                    <i class="fas fa-random"></i> Tentukan Acak
                </button>
                <button type="button" class="btn m-0 p-0 mr-3 p-1 btn-warning" data-toggle="modal" data-target="#gantiManual">
                    <i class="fas fa-edit"></i> Ganti Manual
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100 table-sm" id="tabel_data_cluster_awal">
                <thead>
                    <tr style="text-align: center;">
                    {{-- <th>No</th> --}}
                    <th>ID</th>
                    <th>Cluster</th>
                    <th>Garis Kemiskinan</th>
                    <th>Upah Minimum</th>
                    <th>Pengeluaran</th>
                    <th>Rata-rata Upah</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- /.card-body -->
    </div>

    {{-- Button --}}
    <hr>
    <div class="card" style="background-color: none; border-color: transparent">
        <button type="button" class="btn btn-danger btn-lg" id="btnJalankanKMeans">
            <i class="fas fa-play"></i>  Jalankan K-Means Clustering Sekarang
        </button>
    </div>
    <hr>

    {{-- Iterasi jarak --}}
    <div class="row">
        <div class="col-md-9">
            <div class="card card-navy">
                <div class="card-header">
                    <h2 class="card-title font-weight-bold" style="font-size: 22px">Iterasi Jarak</h2>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped w-100 table-sm" id="tabel_data_iterasi">
                        <thead>
                            <tr style="text-align: center;">
                            {{-- <th>No</th> --}}
                            <th>No</th>
                            <th>Provinsi</th>
                            <th>Tahun</th>
                            <th>Jarak C1</th>
                            <th>Jarak C2</th>
                            <th>Jarak C3</th>
                            <th>C Terkecil</th>
                            <th>Cluster</th>
                            <th>Jarak Minimum</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-navy">
                <div class="card-header bg-navy">
                    <h2 class="card-title font-weight-bold" style="font-size: 22px">SSE <small>(Sum of Squared Error)</small></h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped w-100 table-sm" id="tabel_data_sse">
                        <thead>
                            <tr style="text-align: center;">
                                <th>SSE</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- /.card-body -->
            </div>
        </div>
    </div>

    

    {{-- Visualisasi elbow dan silhouette score untuk untuk validasi jumlah K yang ideal --}}
    <div class="row">
        <div class="col-md-6">
            {{-- <div class="card">
                <div class="card-header bg-navy">
                    <h5 class="font-bold">SSE per Iterasi <small>(Semakin kecil semakin optimal)</small></h5>
                </div>
                <div class="card-body">
                    <canvas id="sseChart" height="150"></canvas>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-header bg-navy">
                    <h5 class="font-bold">Validasi Jumlah Cluster Optimal (Elbow Method)</h5>
                </div>
                <div class="card-body">
                    <canvas id="elbowChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-navy">
                    <div class="row">
                        <div class="col-md-5">
                            <h5 class="font-bold">
                                Rata-Rata Silhouette Score :
                            </h5> 
                        </div>
                        <div class="col-md-7 font-weight-bold" style="font-size: 18px">
                            <div id="silhouette-score">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="silhouetteChart" style="max-width:600px; max-height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Centroid baru / akhir --}}
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Centroid Baru</h2>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100 table-sm" id="tabel_data_cluster_baru">
                <thead>
                    <tr style="text-align: center;">
                    {{-- <th>No</th> --}}
                    <th>No</th>
                    <th>Cluster</th>
                    <th>Garis Kemiskinan</th>
                    <th>Upah Minimum</th>
                    <th>Pengeluaran</th>
                    <th>Rata-rata Upah</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- /.card-body -->
    </div>

    {{-- Keanggotaan cluster akhir --}}
    <div class="card card-info">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Keanggotaan Cluster Akhir</h2>
            <div class="card-tools">
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
            <table class="table table-bordered table-striped w-100 table-sm" id="tabel_hasil">
                <thead>
                    <tr style="text-align: center;">
                    {{-- <th>No</th> --}}
                    <th>No</th>
                    <th>Kriteria</th> {{-- cluster --}}
                    <th>Provinsi</th>
                    <th>Tahun</th>
                    <th>Garis Kemiskinan</th>
                    <th>Upah Minimum</th>
                    <th>Pengeluaran</th>
                    <th>Rata-rata Upah</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- /.card-body -->
    </div>

    {{-- Modal Ganti Manual --}}
    <div class="modal fade" id="gantiManual" tabindex="-1" role="dialog" aria-labelledby="gantiManualLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formGantiManual" method="POST" action="{{ route('ganti.manual.cluster') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title">Ganti Manual Centroid Awal</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Informasi</strong> Batasan input:
                        <ul>
                            <li>Garis Kemiskinan, Upah Minimum, Pengeluaran: Maksimal 7 digit.</li>
                            <li>RR Upah: Maksimal 5 digit.</li>
                        </ul></p>
                        <hr>
                        @for($i = 0; $i < 3; $i++)
                            <h6 class="font-weight-bold mt-3">Cluster {{ $i+1 }}</h6>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Garis Kemiskinan</label>
                                    <input type="number" step="0.01" name="data[{{ $i }}][garis_kemiskinan]" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Upah Minimum</label>
                                    <input type="number" step="0.01" name="data[{{ $i }}][upah_minimum]" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Pengeluaran</label>
                                    <input type="number" step="0.01" name="data[{{ $i }}][pengeluaran]" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>RR Upah</label>
                                    <input type="number" step="0.01" name="data[{{ $i }}][rr_upah]" class="form-control" required>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>  Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal edit cluster awal --}}
    <div class="modal fade" id="editClusterAwal" tabindex="-1" role="dialog" aria-labelledby="editClusterAwalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditClusterAwal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title">Edit Cluster Awal</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_data_pekerja" id="edit_id_data_pekerja">
                        <div class="form-group">
                            <label>Cluster</label>
                            <input type="text" class="form-control" name="cluster" id="edit_cluster" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Garis Kemiskinan</label>
                                    <input type="number" step="0.01" class="form-control" name="garis_kemiskinan" id="edit_garis_kemiskinan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upah Minimum</label>
                                    <input type="number" step="0.01" class="form-control" name="upah_minimum" id="edit_upah_minimum" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pengeluaran</label>
                                    <input type="number" step="0.01" class="form-control" name="pengeluaran" id="edit_pengeluaran" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RR Upah</label>
                                    <input type="number" step="0.01" class="form-control" name="rr_upah" id="edit_rr_upah" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>  Perbarui</button>
                    </div>
                </div>
            </form>
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
                type: "GET"
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
                }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 1, "asc" ], [ 2, "asc" ]],
        });

        var dataClusterAwal = $('#tabel_data_cluster_awal').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_data_cluster_awal') }}",
                dataType: "json",
                type: "GET"
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    // data: "id_iterasi_cluster_awal",
                    className: "text-center",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "cluster.cluster",
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
            dom: 't',
        });

        var dataDataIterasi = $('#tabel_data_iterasi').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_data_iterasi') }}",
                dataType: "json",
                type: "GET",
                data: function (d) {
                    d.id_provinsi = $('#id_provinsi').val();
                }
            },
            columns: [
                {
                    // data: "DT_RowIndex",
                    data: "id_iterasi_jarak",
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
                }
            ]
        });

        var dataDataIterasi = $('#tabel_data_sse').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_iterasi_sse') }}",
                dataType: "json",
                type: "GET"
            },
            columns: [
                {
                    data: "sse",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                }
            ],
            dom: 't',
        });

        var dataClusterAwal = $('#tabel_data_cluster_baru').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_iterasi_cluster_baru') }}",
                dataType: "json",
                type: "GET"
            },
            columns: [
                {
                    // data: "DT_RowIndex",
                    data: "id_iterasi_cluster_baru",
                    className: "text-center",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "cluster.cluster",
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
                }
            ],
            dom: 't',
        });

        var dataHasil = $('#tabel_hasil').DataTable({
            serverSide: false,
            ajax: {
                url: "{{ url('admin/list_data_hasil_akhir') }}",
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
                    data: "cluster.nama_cluster",
                    // data: "cluster.cluster",
                    className: "text-center",
                    orderable: true,
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
                }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 2, "asc" ], [ 3, "asc" ]],
        });
        $('#id_provinsi').on('change',function() {
            dataHasil.ajax.reload();
        });
        
    });
    $('#btnAcak').on('click', function () {
        $.ajax({
            url: '{{ route('cluster-awal.acak') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                // alert(response.message);
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data centroid berhasil diperbarui.',
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#tabel_data_cluster_awal').DataTable().ajax.reload(); // reload datatable
            },
            error: function (xhr) {
                // alert('Terjadi kesalahan.');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui data centroid awal.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });


    $('#formGantiManual').on('submit', function (event) {
        event.preventDefault(); // Cegah submit default

        let messages = [];
        let isValid = true;

        // Validasi angka maksimal digit
        $('input[name*="garis_kemiskinan"], input[name*="upah_minimum"], input[name*="pengeluaran"]').each(function () {
            let value = $(this).val().replace('.', '').replace(',', '');
            if (value.length > 7) {
                isValid = false;
                messages.push("Garis Kemiskinan, Upah Minimum, dan Pengeluaran maksimal 7 digit.");
                return false;
            }
        });

        $('input[name*="rr_upah"]').each(function () {
            let value = $(this).val().replace('.', '').replace(',', '');
            if (value.length > 5) {
                isValid = false;
                messages.push("RR Upah maksimal 5 digit.");
                return false;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal!',
                text: messages.join("\n"),
                showConfirmButton: true
            });
            return;
        }

        // Kirim data via AJAX
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#gantiManual').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data centroid awal berhasil diganti.',
                    showConfirmButton: false,
                    timer: 1500
                });

                // Optional reload
                setTimeout(function () {
                    location.reload();
                }, 1600);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan centroid.',
                    showConfirmButton: true
                });
            }
        });
    });


    // Edit Cluster Awal //
    $(document).on('click', '.btn-edit', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/admin/edit-cluster-awal/' + id,
            type: 'GET',
            success: function (data) {
                // Isi data ke dalam form modal
                $('#edit_id_data_pekerja').val(data.data_cluster_awal.id_data_pekerja);
                $('#edit_cluster').val(data.data_cluster_awal.cluster);
                $('#edit_garis_kemiskinan').val(data.data_cluster_awal.garis_kemiskinan);
                $('#edit_upah_minimum').val(data.data_cluster_awal.upah_minimum);
                $('#edit_pengeluaran').val(data.data_cluster_awal.pengeluaran);
                $('#edit_rr_upah').val(data.data_cluster_awal.rr_upah);
                // Set action form
                $('#formEditClusterAwal').attr('action', '/admin/update-cluster-awal/' + id);
                // Tampilkan modal
                $('#editClusterAwal').modal('show');
            },
            error: function () {
                // alert('Gagal mengambil data.');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengubah data.',
                    showConfirmButton: true
                });
            }
        });
    });
    // Submit Form Edit Cluster Awal
    $('#formEditClusterAwal').on('submit', function (e) {
        e.preventDefault(); // Mencegah form submit default
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#editClusterAwal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data centroid awal berhasil diperbarui.',
                    showConfirmButton: false,
                    timer: 1500
                });

                // Optional: reload table atau halaman
                setTimeout(function () {
                    location.reload();
                }, 1600);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui data.',
                    showConfirmButton: true
                });
            }
        });
    });


    // K-Means Clustering //
    $('#btnJalankanKMeans').click(function() {
        Swal.fire({
            title: 'Yakin?',
            text: "Yakin ingin menjalankan K-Means Clustering sekarang?. Proses ini akan memakan waktu beberapa saat.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, jalankan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('jalankan.kmeans') }}", // route ini harus kamu definisikan
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#btnJalankanKMeans').html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
                        $('#btnJalankanKMeans').prop('disabled', true);
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Clustering selesai!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#btnJalankanKMeans').html('Jalankan K-Means Clustering Sekarang').prop('disabled', false);

                        // Reload semua tabel
                        $('#tabel_data_iterasi').DataTable().ajax.reload();
                        $('#tabel_data_sse').DataTable().ajax.reload();
                        $('#tabel_data_cluster_baru').DataTable().ajax.reload();
                        $('#tabel_hasil').DataTable().ajax.reload();
                    },
                    error: function(err) {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan.',
                            showConfirmButton: true
                        });
                        $('#btnJalankanKMeans').html('Jalankan K-Means Clustering Sekarang').prop('disabled', false);
                    }
                });
            }
        });
    });

    // Visualisasi SSE Chart
    $(document).ready(function () {
        $.get("{{ route('data.sse') }}", function(data) {
            const ctx = document.getElementById('sseChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'SSE per Iterasi',
                        data: data.values,
                        fill: true,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.3,
                        pointBackgroundColor: 'rgb(0, 123, 255)',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'SSE per Iterasi'
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Iterasi ke-' }
                        },
                        y: {
                            title: { display: true, text: 'SSE (Sum of Squared Errors)' },
                            beginAtZero: false
                        }
                    },
                    // scales: {
                    //     xAxes: [{ 
                    //         scaleLabel: { display: true, labelString: 'Iterasi ke-' } 
                    //     }],
                    //     yAxes: [{ 
                    //         scaleLabel: { display: true, labelString: 'SSE (Sum of Squared Errors)' } 
                    //     }]
                    // },
                }
            });
        });
    });

    // Visualisasi Elbow Chart
    $(document).ready(function () {
        $.get("{{ route('data.elbow') }}", function (data) {
            const ctx = document.getElementById('elbowChart').getContext('2d');
            const kValues = data.map(item => item.k);
            const sseValues = data.map(item => item.sse);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: kValues,
                    datasets: [{
                        label: 'SSE vs Jumlah Cluster (K)',
                        data: sseValues,
                        fill: false,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.3,
                        pointBackgroundColor: 'rgb(255, 99, 132)',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Elbow Method - Tentukan K Optimal'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Jumlah Cluster (K)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'SSE (Sum of Squared Errors)'
                            }
                        }
                    }
                }
            });
        });
    });

    // silhouette score // /// // /// //
    // async function fetchSilhouetteScore() {
    //     try {
    //         let response = await fetch('{{ route("silhouette.score") }}');
    //         let data = await response.json();
    //         if(data.silhouette_score !== null) {
    //             document.getElementById('silhouette-score').innerText = data.silhouette_score;
    //         } else {
    //             document.getElementById('silhouette-score').innerText = data.message || 'Tidak ada data';
    //         }
    //     } catch (error) {
    //         document.getElementById('silhouette-score').innerText = 'Error mengambil data';
    //         console.error(error);
    //     }
    // }
    // // Panggil saat halaman siap
    // window.addEventListener('DOMContentLoaded', fetchSilhouetteScore);

    async function fetchSilhouetteScore() {
        try {
            let response = await fetch('{{ route("silhouette.score") }}');
            let data = await response.json();

            if(data.silhouette_score !== null) {
                document.getElementById('silhouette-score').innerText = data.silhouette_score;

                // Siapkan data grafik per cluster
                const labels = Object.keys(data.per_cluster).map(c => 'Cluster ' + c);
                const values = Object.values(data.per_cluster).map(v => parseFloat(v.toFixed(4)));

                // Render chart
                const ctx = document.getElementById('silhouetteChart').getContext('2d');

                // Jika chart sudah pernah dibuat, destroy dulu supaya gak duplikat
                if(window.silhouetteChartInstance) {
                    window.silhouetteChartInstance.destroy();
                }

                window.silhouetteChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Rata-rata Silhouette Score per Cluster',
                            data: values,
                            backgroundColor: ['#34C759', '#E74C3C', '#F1C40F'],
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 1
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ctx.parsed.y.toFixed(4)
                                }
                            }
                        }
                    }
                });

            } else {
                document.getElementById('silhouette-score').innerText = data.message || 'Tidak ada data';
            }
        } catch (error) {
            document.getElementById('silhouette-score').innerText = 'Error mengambil data';
            console.error(error);
        }
    }

    window.addEventListener('DOMContentLoaded', fetchSilhouetteScore);


</script>
@endpush