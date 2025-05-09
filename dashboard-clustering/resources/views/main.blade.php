@extends('layouts_guest.template')

@section('content')
    <div class="container"> {{-- centered view --}}

      {{-- Opsi Carousel --}}
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100 img-fluid" src="{{asset('img/kesejahteraan_pekerja.png')}}" alt="First slide" style="height: 300px; filter: blur(0px) brightness(0.5);">
            <div class="carousel-caption d-none d-md-block text-dark" style="position: absolute; top: 20%; left: 10%; width: 80%; text-align: center;">
              <h1 class="text-white" style="text-shadow: 2px 2px 4px #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 30px;">
                {{-- Kesejahteraan pekerja merupakan pemenuhan kebutuhan jasmani dan rohani, baik dalam maupun luar hubungan kerja, yang dapat meningkatkan produktivitas kerja👩‍💼👨‍💼 --}}
                {{-- , dengan fokus pada peningkatan upah minimum, perlindungan jaminan sosial, dan menciptakan lingkungan kerja yang aman dan sehat. --}}
              </h1>
            </div>
          </div>
          <div class="carousel-item">
            <img class="d-block w-100 img-fluid" src="{{asset('img/cluster_cyber.jpg')}}" alt="Second slide" style="height: 300px; filter: blur(2.5px) brightness(0.7);">
            <div class="carousel-caption d-none d-md-block text-dark" style="position: absolute; top: 30%; left: 10%; width: 80%; text-align: center;">
              <h1 class="text-white" style="text-shadow: 2px 2px 4px #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 30px;">
                {{-- Implementasi K-Means Clustering untuk Mengelompokkan Data Kesejahteraan Pekerja di Indonesia💻 --}}
              </h1>
            </div>
          </div>
          <div class="carousel-item">
            <img class="d-block w-100 img-fluid" src="{{asset('img/cover_map.jpg')}}" alt="Third slide" style="height: 300px; filter: blur(2.5px) brightness(0.7);">
            <div class="carousel-caption d-none d-md-block text-dark" style="position: absolute; top: 30%; left: 10%; width: 80%; text-align: center;">
              <h1 class="text-white" style="text-shadow: 2px 2px 4px #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 30px;">
                Telusuri Peta Interaktif untuk Melihat Cluster Kesejahteraan Pekerja di Seluruh Indonesia 🗺️
              </h1>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      {{-- // Opsi Carousel --}}

      <div class="row mt-5">
        <div class="col-md-6 pr-5">
          <h1 class="font-weight-bold ">Data Cluster Kesejahteraan Pekerja di Indonesia</h1>
          <p style="text-align: justify;">
            Dalam penelitian ini, dataset yang digunakan adalah dataset Kesejahteraan Pekerja Indonesia. Data ini bersumber 
            dari BPS (Badan Pusat Statistik). Terdapat 4 tabel diantaranya garis kemiskinan, minimal upah, pengeluaran, 
            dan rata-rata upah
          </p>
          <a href="{{ url('/lihat_peta')}}" class="btn btn-outline-info rounded-pill" style="border: 2px solid #17a2b8;">
            <i class="fas fa-map-marked-alt" aria-hidden="true"></i> Lihat Peta Interaktif
          </a>
        </div>
        <div class="col-md-6 pl-5">
          {{-- <div class="card bg-light" style="border-color: black; border: 4px;">
            <div class="card-header">
              Algoritma Apa yang digunakan??
            </div>
            <div class="card-body">
    
              <p class="card-text" style="text-align: justify;">
                Algoritma K-Means Clustering, yakni pengelompokan berbasis centroid berulang yang mempartisi 
                kumpulan data menjadi kelompok serupa berdasarkan jarak antara centroid mereka
              </p>
    
            </div>
          </div> --}}
          <h1 class="font-weight-bold text-right">Algoritma apa yang digunakan?</h1>
          <p style="text-align: right;">
            Algoritma K-Means Clustering, yakni pengelompokan berbasis centroid berulang yang mempartisi 
            kumpulan data menjadi kelompok serupa berdasarkan jarak antara centroid mereka, lihat iterasi terakhir : 
          </p>
          <div style="text-align:right;">
            <a href="{{ url('/lihat_data')}}" class="btn btn-outline-success rounded-pill" style="border: 2px solid #17b87d;">
              <i class="fas fa-chart-bar" aria-hidden="true"></i> Lihat Iterasi Terakhir
            </a>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
@endsection

@push('css')
@endpush

@push('js')
@endpush