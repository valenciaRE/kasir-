<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS APP</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

  <style>
    .brand-link { border-bottom: none !important; }
    .main-sidebar { elevation: 4; }
    .nav-sidebar .nav-link.active {
      background-color: #ffffff !important;
      color: #343a40 !important;
    }
    .btn-link:focus,
    .btn-link:hover {
      text-decoration: none !important;
    }

    /* ===== FIX FOOTER ADMINLTE (FINAL) ===== */
    html, body {
      height: 100%;
    }

    .content-wrapper {
      min-height: calc(100vh - 114px);
    }
    /* ===================================== */
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
@include('sweetalert::alert')

<div class="wrapper">

  <!-- NAVBAR -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/dashboard" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      @auth
      <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
          {{ ucwords(auth()->user()->name) }}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <button type="button" class="dropdown-item" data-toggle="modal" data-target="#formGantiPassword">
            Ganti Password
          </button>

          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="dropdown-item text-danger font-weight-bold">
              Logout
            </button>
          </form>
        </div>
      </div>
      @endauth
    </ul>
  </nav>

  <!-- SIDEBAR & MODAL -->
  <x-user.form-ganti-password />
  <x-admin.aside />

  <!-- CONTENT -->
  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('content_title')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">@yield('content_title')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>

  </div>

  <!-- FOOTER -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      PT POS APP
    </div>
    <strong>
      &copy; {{ date('Y') }} PT POS APP.
    </strong>
    All rights reserved.
  </footer>

</div>

<!-- SCRIPTS -->
<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('adminlte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
  $(function () {
    $('#table1').DataTable({
      responsive: true,
      autoWidth: false,
      lengthChange: true
    });
  });
</script>

@stack('script')

</body>
</html>
