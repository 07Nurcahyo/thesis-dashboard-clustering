<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  {{-- sweetalert2 --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css">

  @stack('css')
</head>
<body class="hold-transition login-page">

<div class="login-box" style="border-radius: 15px; overflow: hidden; width: 900px; margin: auto;">
  <!-- /.login-logo -->
  <div class="card">
    <div class="row">
      <div class="col d-flex justify-content-center align-items-center">
        <img src="{{ asset('img/employee_data.png') }}" alt="Logo" class="img-fluid" style="width: 90%; height: auto;">
      </div>
      <div class="col d-flex justify-content-center align-items-center">
        <div class="card-body login-card-body pr-5">
          <div class="login-logo">
            <a><b class="text-bold">Login Admin</b></a>
          </div>
          {{-- <p class="login-box-msg">Login untuk masuk ke halaman admin</p> --}}
          @error('error')
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{$message}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @enderror
          <form action="{{ url('proses_login') }}" method="post" id="login_">
            @csrf
            <div class="input-group mb-3">
              <input type="username" class="form-control" name="username" id="username" placeholder="Username" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" onclick="loginConfirm('Berhasil login, tunggu sebentar!🗿')">Login</button>
            <a href="{{ url('/') }}" class="btn btn-warning btn-block">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
{{-- sweetalert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // untuk mengirimkan token laravel csrf pada setiap request ajax
  $.ajaxSetup({headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}});
</script>
<script>
    // $(document).ready(function() {
    //     $('#login_').submit(function(e) {
    //         e.preventDefault();
    //         var form = $(this);
    //         var url = form.attr('action');
    //         $.ajax({
    //             type: "POST",
    //             url: url,
    //             data: form.serialize(),
    //             success: function(data) {
    //                 if (data.status == 'success') {
    //                     loginConfirm('Berhasil login, tunggu sebentar!');
    //                 } else {
    //                     Swal.fire({
    //                         title: "Gagal Login",
    //                         text: data.message,
    //                         icon: "error",
    //                         position: "top-end",
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                 }
    //             }
    //         });
    //     });
    // });
</script>
@stack('js')
</body>
</html>