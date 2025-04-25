@extends('layouts_guest.template')

@section('content')
    <div class="container">
        <!-- Main row -->
        <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
            <!-- MAP & BOX PANE -->
            <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Peta Informasi Kesejahteraan Pekerja di Indonesia berdasarkan Provinsi</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="d-md-flex">
                <div class="p-1 flex-fill" style="overflow: hidden">
                    <!-- Map will be created here -->
                    <div id="world-map-markers" style="height: 525px; overflow: hidden">
                    <div class="vmap" id="vmap" style="height: 100%"></div>
                    </div>
                </div>
                <div class="card-pane-right bg-secondary pt-2 pb-2 pl-4 pr-4">
                    <div class="description-block mb-4">
                    <div class="sparkbar pad" data-color="">90,70,90,70,75,80,70</div>
                    <h5 class="description-header">8390</h5>
                    <span class="description-text">Lorem ipsum</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block mb-4">
                    <div class="sparkbar pad" data-color="">90,50,90,70,61,83,63</div>
                    <h5 class="description-header">30%</h5>
                    <span class="description-text">Lorem ipsum</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block">
                    <div class="sparkbar pad" data-color="">90,50,90,70,61,83,63</div>
                    <h5 class="description-header">70%</h5>
                    <span class="description-text">Lorem ipsum</span>
                    </div>
                    <!-- /.description-block -->
                </div><!-- /.card-pane-right -->
                </div><!-- /.d-md-flex -->
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Latest Orders</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table m-0">
                    <thead>
                    <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Popularity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="badge badge-success">Shipped</span></td>
                    <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="badge badge-danger">Delivered</span></td>
                    <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="badge badge-info">Processing</span></td>
                    <td>
                        <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="badge badge-danger">Delivered</span></td>
                    <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                    </td>
                    </tr>
                    <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="badge badge-success">Shipped</span></td>
                    <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                    </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
            </div>
            <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('css')
@endpush

@push('js')
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#vmap').vectorMap(
    {
        map: 'indonesia_id',
        backgroundColor: '#a5bfdd',
        borderColor: 'black',
        borderOpacity: 0.25,
        borderWidth: 1,
        color: 'green',
        enableZoom: true,
        hoverColor: '#c9dfaf',
        hoverOpacity: null,
        normalizeFunction: 'linear',
        scaleColors: ['#b6d6ff', '#005ace'],
        selectedColor: '#c9dfaf',
        selectedRegions: null,
        showTooltip: true,
        onRegionClick: function(element, code, region)
        {
            var message = 'You clicked "'
                + region
                + '" which has the code: '
                + code.toUpperCase();

            alert(message);
        }
    });
});
</script>
@endpush