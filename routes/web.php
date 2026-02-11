<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PengeluaranBarangController;
use App\Http\Controllers\KasirController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login.form');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login');

    Route::get('/register', fn () => view('auth.register'))
        ->name('register.form');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    | DASHBOARD
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | USERS
    */
    Route::prefix('users')->as('users.')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/gantipassword', 'gantiPassword')->name('ganti-password');
            Route::post('/reset-password', 'resetPassword')->name('reset-password');
        });

    /*
    | MASTER DATA
    */
    Route::prefix('master-data')->as('master-data.')->group(function () {

        Route::prefix('kategori')->as('kategori.')
            ->controller(KategoriController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::delete('/{id}/destroy', 'destroy')->name('destroy');
            });

        Route::prefix('product')->as('product.')
            ->controller(ProductController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::delete('/{id}/destroy', 'destroy')->name('destroy');
            });
    });

    /*
    | PENERIMAAN BARANG
    */
    Route::prefix('penerimaan-barang')->as('penerimaan-barang.')
        ->controller(PenerimaanBarangController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

    /*
    | LAPORAN
    */
    Route::prefix('laporan')->as('laporan.')->group(function () {

        Route::prefix('penerimaan-barang')->as('penerimaan-barang.')
            ->controller(PenerimaanBarangController::class)
            ->group(function () {
                Route::get('/', 'laporan')->name('laporan');
                Route::get('/{nomor_penerimaan}/detail', 'detailLaporan')
                    ->name('detail-laporan');
            });

        Route::prefix('pengeluaran-barang')->as('pengeluaran-barang.')
            ->controller(PengeluaranBarangController::class)
            ->group(function () {
                Route::get('/', 'laporan')->name('laporan');
                Route::get('/{nomor_pengeluaran}/detail', 'detailLaporan')
                    ->name('detail-laporan');
            });
    });
});

/*
|--------------------------------------------------------------------------
| KASIR AREA (POS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->group(function () {

    /*
    | KASIR (POS)
    */
    Route::get('/kasir', [KasirController::class, 'index'])
        ->name('kasir.index');

    Route::post('/kasir', [KasirController::class, 'store'])
        ->name('kasir.store');

    /*
    | MIDTRANS QRIS TOKEN
    */
    Route::post('/kasir/midtrans-token', [KasirController::class, 'midtransToken'])
        ->name('kasir.midtrans.token');

    /*
    | PENGELUARAN BARANG
    */
    Route::prefix('pengeluaran-barang')->as('pengeluaran-barang.')
        ->controller(PengeluaranBarangController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/print', 'print')->name('print');
        });

    /*
    | GET DATA (AJAX)
    */
    Route::prefix('get-data')->as('get-data.')->group(function () {
        Route::get('/produk', [ProductController::class, 'getData'])->name('produk');
        Route::get('/cek-stok-produk', [ProductController::class, 'cekStok'])->name('cek-stok');
        Route::get('/cek-harga-produk', [ProductController::class, 'cekHarga'])->name('cek-harga');
    });
});
