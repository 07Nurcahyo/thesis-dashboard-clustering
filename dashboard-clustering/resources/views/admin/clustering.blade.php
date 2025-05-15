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
                    <th>ID</th>
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

    {{-- Cluster awal --}}
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Cluster Awal</h2>
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
    <div class="card" style="background-color: transparent; border-color: transparent">
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-danger btn-lg btn-block">Jalankan K-Means Clustering Sekarang</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-success btn-lg btn-block">Kembalikan ke Data Cluster Tahun 2024</button>
            </div>
        </div>
    </div>

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
                            <th>ID</th>
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

    {{-- Cluster baru / akhir --}}
    <div class="card card-navy">
        <div class="card-header">
            <h2 class="card-title font-weight-bold" style="font-size: 22px">Cluster Baru</h2>
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
                    <th>ID</th>
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
                    <th>ID</th>
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
                    <div class="modal-header">
                        <h5 class="modal-title">Ganti Manual</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Informasi</strong> Batasan input:
                        <ul>
                            <li>Garis Kemiskinan, Upah Minimum, Pengeluaran: Maksimal 7 digit.</li>
                            <li>RR Upah: Maksimal 5 digit.</li>
                        </ul></p>
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
                        <button type="submit" class="btn btn-success">Simpan</button>
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
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Cluster Awal</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_data_pekerja" id="edit_id_data_pekerja">
                        <div class="form-group">
                            <label>Cluster</label>
                            <input type="text" class="form-control" name="cluster" id="edit_cluster" readonly>
                        </div>
                        <div class="form-group">
                            <label>Garis Kemiskinan</label>
                            <input type="number" step="0.01" class="form-control" name="garis_kemiskinan" id="edit_garis_kemiskinan" required>
                        </div>
                        <div class="form-group">
                            <label>Upah Minimum</label>
                            <input type="number" step="0.01" class="form-control" name="upah_minimum" id="edit_upah_minimum" required>
                        </div>
                        <div class="form-group">
                            <label>Pengeluaran</label>
                            <input type="number" step="0.01" class="form-control" name="pengeluaran" id="edit_pengeluaran" required>
                        </div>
                        <div class="form-group">
                            <label>RR Upah</label>
                            <input type="number" step="0.01" class="form-control" name="rr_upah" id="edit_rr_upah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
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
                    // data: "DT_RowIndex",
                    data: "id",
                    className: "text-center",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "cluster.nama_cluster",
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
                alert(response.message);
                $('#tabel_data_cluster_awal').DataTable().ajax.reload(); // reload datatable
            },
            error: function (xhr) {
                alert('Terjadi kesalahan.');
            }
        });
    });

    // Ganti Manual //
    // Modal reset setelah close
    // $('#gantiManual').on('hidden.bs.modal', function () {
    //     $(this).find('form')[0].reset();
    // });
    // Validasi input agar tidak melebihi jumlah digit
    $('#formGantiManual').on('submit', function (event) {
        let messages = [];
        // Validasi ID Pekerja, Garis Kemiskinan, Upah Minimum, Pengeluaran (max 7 digit)
        $('input[name*="garis_kemiskinan"], input[name*="upah_minimum"], input[name*="pengeluaran"]').each(function() {
            let value = $(this).val();
            if (value.length > 7) {
                messages.push("Maksimal 7 digit untuk ID Pekerja, Garis Kemiskinan, Upah Minimum, dan Pengeluaran.");
            }
        });
        // Validasi RR Upah (max 5 digit)
        $('input[name*="rr_upah"]').each(function() {
            let value = $(this).val();
            if (value.length > 5) {
                messages.push("Maksimal 5 digit untuk RR Upah.");
            }
        });
        if (messages.length > 0) {
            alert(messages.join("\n"));
            event.preventDefault();
            return false;
        }
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
                alert('Gagal mengambil data.');
            }
        });
    });

</script>
@endpush