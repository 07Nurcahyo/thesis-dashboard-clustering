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
                <button type="button" class="btn m-0 p-0 mr-3 p-1 btn-warning">
                    <i class="fas fa-plus-square"></i> Ganti Manual
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
            ]
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
                    // data: "DT_RowIndex",
                    data: "id_iterasi_cluster_awal",
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
    
    // $('#btnTentukanAcak').on('click', function () {
    //     // Buat array acak
    //     const randomCluster = [];
    //     for (let i = 1; i <= 3; i++) {
    //         randomCluster.push({
    //             cluster: 'C' + i,
    //             garis_kemiskinan: Math.floor(1000000 + Math.random() * 9000000), // 7 digit
    //             upah_minimum: Math.floor(1000000 + Math.random() * 9000000),     // 7 digit
    //             pengeluaran: Math.floor(1000000 + Math.random() * 9000000),      // 7 digit
    //             rr_upah: Math.floor(10000 + Math.random() * 90000)               // 5 digit
    //         });
    //     }

    //     // Kirim ke backend (jika Anda ingin simpan ke DB), atau langsung tampilkan di tabel sementara
    //     // Contoh: tampilkan ke tabel dengan DataTable.clear() dan .rows.add()
    //     let table = $('#tabel_data_cluster_awal').DataTable();
    //     table.clear(); // kosongkan
    //     randomCluster.forEach((data, idx) => {
    //         table.row.add({
    //             id_iterasi_cluster_awal: idx + 1,
    //             cluster: { cluster: data.cluster },
    //             garis_kemiskinan: data.garis_kemiskinan,
    //             upah_minimum: data.upah_minimum,
    //             pengeluaran: data.pengeluaran,
    //             rr_upah: data.rr_upah,
    //             aksi: '<button class="btn btn-danger btn-sm">Hapus</button>'
    //         });
    //     });
    //     table.draw();
    // });
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



</script>
@endpush