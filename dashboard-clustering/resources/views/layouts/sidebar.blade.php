<div class="sidebar">
    <!-- Sidebar user (optional) -->
    {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div> --}}

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{url('admin/dashboard')}}" class="nav-link {{ ($activeMenu == 'dashboard')? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-header">KELOLA DATA</li>
        <li class="nav-item">
          <a href="{{url('admin/kelola_data')}}" class="nav-link {{ ($activeMenu == 'kelola_data')? 'active' : '' }}">
            <i class="nav-icon fas fa-copy"></i>
            <p>Kelola Data</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{url('admin/clustering')}}" class="nav-link {{ ($activeMenu == 'clustering')? 'active' : '' }}">
          {{-- <a href="" class="nav-link"> --}}
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>K-Means Clustering</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>