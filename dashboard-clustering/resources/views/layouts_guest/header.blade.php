<nav class="main-header navbar navbar-expand-lg navbar-light navbar-white">
    <a href="" class="navbar-brand">
      <img src="{{asset('img/clustering.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold">CLUSTERING</span>
    </a>

    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Right navbar links -->
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'home')? 'active font-weight-bold' : '' }}">Home</a>
        </li>
        <li class="nav-item">
          <a href="{{  url('/lihat_data')}}" class="nav-link {{ ($activeMenu == 'lihat_data')? 'active font-weight-bold' : '' }}">Lihat Data</a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/lihat_grafik') }}" class="nav-link {{ ($activeMenu == 'lihat_grafik')? 'active font-weight-bold' : '' }}">Lihat Grafik & Statistik</a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/lihat_peta')}}" class="nav-link {{ ($activeMenu == 'lihat_peta')? 'active font-weight-bold' : '' }}">Lihat Peta Interaktif</a>
        </li>
        <li class="nav-item">
          <a href="login_admin" class="nav-link btn btn-primary rounded-pill btn-login-admin">
            <i class="fas fa-user-cog mr-1 ml-1" aria-hidden="true"></i> Login Admin
          </a>
        </li>
      </ul>
    </ul>
</nav>