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
<div class="login-box">
  <div class="login-logo">
    <a><b>Admin</b>Clustering</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Login untuk masuk ke halaman admin</p>
      @error('error')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{$message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @enderror
      <form action="{{ url('proses_login') }}" method="post" id="login_">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
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
        <div class="row">
          <div class="col-4">
            {{-- <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Ingat Saya
              </label>
            </div> --}}
            <button type="submit" class="btn btn-primary btn-block" onclick="loginConfirm('Berhasil login, tunggu sebentar!🗿')">Login</button>
          </div>
          <!-- /.col -->
          <div class="col-4">
              <a href="{{ url('/') }}" class="btn btn-warning btn-block">Kembali</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      {{-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> --}}
      <!-- /.social-auth-links -->

      {{-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>
    </div> --}}
    <!-- /.login-card-body -->
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
    //     loginConfirm = function(text) {
    //         console.log('#login_');
    //         event.preventDefault();
    //         Swal.fire({
    //             title: "Berhasil Login",
    //             text: text,
    //             icon: "success",
    //             position: "top-end"
    //         }).then((result) => {
    //             $('#login_').submit();
    //         });
    //     }
    // });

    // $(document).ready(function() {
    //     $('#login_').on('submit', function(event) {
    //         event.preventDefault();
    //         $.ajax({
    //             type: 'POST',
    //             url: $(this).attr('action'),
    //             data: $(this).serialize(),
    //             success: function(response) {
    //                 if (response.success) {
    //                     Swal.fire({
    //                         title: "Berhasil Login",
    //                         text: "Berhasil login, tunggu sebentar!🗿",
    //                         icon: "success"
    //                     }).then(() => {
    //                         $('#login_')[0].submit();
    //                     });
    //                 } else {
    //                     Swal.fire({
    //                         title: "Login Gagal",
    //                         text: "Username atau password salah!",
    //                         icon: "error"
    //                     });
    //                 }
    //             },
    //             error: function() {
    //                 Swal.fire({
    //                     title: "Login Error",
    //                     text: "Terjadi kesalahan saat memproses permintaan.",
    //                     icon: "error"
    //                 });
    //             }
    //         });
    //     });
    // });
</script>
@stack('js')
</body>
</html>