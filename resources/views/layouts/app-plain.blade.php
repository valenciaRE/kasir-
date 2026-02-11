<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'POS APP') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- SELECT2 CSS (INI YANG KURANG) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet">

    @stack('css')
</head>
<body class="bg-light">

{{-- TOP BAR --}}
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <span class="navbar-brand font-weight-bold">
        🧾 POS KASIR
    </span>

    <div class="d-flex align-items-center">

        <a href="{{ route('kasir.index') }}"
           class="btn btn-sm {{ request()->routeIs('kasir.*') ? 'btn-secondary' : 'btn-outline-secondary' }} mr-2">
            🧾 Kasir
        </a>

        <a href="{{ route('barang-datang') }}"
           class="btn btn-sm {{ request()->routeIs('barang-datang*') ? 'btn-primary' : 'btn-outline-primary' }} mr-2">
            📦 Barang Datang
        </a>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                🚪 Logout
            </button>
        </form>
    </div>
</nav>

{{-- CONTENT --}}
<main class="container-fluid py-3">
    @yield('content')
</main>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- SELECT2 JS (INI YANG PALING PENTING) --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('script')
</body>
</html>