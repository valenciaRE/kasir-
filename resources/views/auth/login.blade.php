<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminLte') }}/plugins/fontawesome-free/css/all.min.css">

  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminLte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('adminLte') }}/dist/css/adminlte.min.css">

  <!-- CUSTOM STYLE (TAMBAHAN SAJA) -->
  <style>
    body.login-page {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .login-box {
      width: 380px;
    }

    .card {
      border-radius: 14px;
      box-shadow: 0 15px 35px rgba(0,0,0,.2);
      overflow: hidden;
    }

    .card-header {
      background: linear-gradient(135deg, #4e73df, #224abe);
      color: #fff;
      border-bottom: none;
      padding: 25px;
    }

    .card-header .h1 {
      font-size: 26px;
      font-weight: 700;
      margin: 0;
    }

    .login-box-msg {
      color: #555;
      font-weight: 500;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 8px;
      height: 45px;
    }

    .input-group-text {
      border-radius: 0 8px 8px 0;
      background: #f4f6f9;
    }

    .btn-primary {
      border-radius: 8px;
      font-weight: 600;
      padding: 10px;
      background: linear-gradient(135deg, #4e73df, #224abe);
      border: none;
    }

    .btn-primary:hover {
      opacity: .9;
    }

    .alert {
      border-radius: 8px;
      font-size: 14px;
    }
  </style>
</head>

<body class="hold-transition login-page">
<div class="login-box">

  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <span class="h1"><b>Admin</b>LTE</span>
    </div>

    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      {{-- ERROR VALIDASI --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- FORM LOGIN --}}
      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-sign-in-alt mr-1"></i> Login
        </button>
      </form>
      {{-- END FORM --}}

    </div>
  </div>

</div>

<!-- jQuery -->
<script src="{{ asset('adminLte') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminLte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('adminLte') }}/dist/js/adminlte.min.js"></script>

</body>
</html>
