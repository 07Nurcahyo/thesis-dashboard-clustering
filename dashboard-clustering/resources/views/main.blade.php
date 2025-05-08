@extends('layouts_guest.template')

@section('content')
    <div class="container"> {{-- centered view --}}

      {{-- Opsi Carousel --}}
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100 img-fluid" src="{{asset('img/cover_map.jpg')}}" alt="First slide" style="height: 300px; filter: blur(2.5px) brightness(0.7);">
            <div class="carousel-caption d-none d-md-block text-dark" style="position: absolute; top: 30%; left: 10%; width: 80%; text-align: center;">
              <h1 class="text-white" style="text-shadow: 2px 2px 4px #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 30px;">
                Telusuri Peta Interaktif untuk Melihat Cluster Kesejahteraan Pekerja di Seluruh Indonesia 🗺️
              </h1>
            </div>
          </div>
          <div class="carousel-item">
            <img class="d-block w-100 img-fluid" src="{{asset('img/city.jpg')}}" alt="Second slide" style="height: 300px;">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100 img-fluid" src="{{asset('img/city.jpg')}}" alt="Third slide" style="height: 300px;">
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
        <div class="col-md-6">
          <h1 class="font-weight-bold ">Data Cluster Kesejahteraan Pekerja di Indonesia</h1>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate, expedita? Saepe quibusdam, aperiam aliquam quas repellendus dolores.</p>
          <a href="{{ url('/lihat_peta')}}" class="btn btn-outline-info rounded-pill" style="border: 2px solid #17a2b8;">
            <i class="fas fa-map-marked-alt" aria-hidden="true"></i> Lihat Peta Interaktif
          </a>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
    
              <p class="card-text">
                Some quick example text to build on the card title and make up the bulk of the card's
                content.
              </p>
    
              <a href="#" class="card-link">Card link</a>
              <a href="#" class="card-link">Another link</a>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
@endsection

@push('css')
@endpush

@push('js')
@endpush