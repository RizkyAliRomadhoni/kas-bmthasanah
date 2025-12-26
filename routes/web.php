<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\KambingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\RincianKambingController;



// =====================================================================
// REDIRECT HALAMAN UTAMA KE DASHBOARD
// =====================================================================
Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');


// =====================================================================
// DASHBOARD — FIXED (Tidak boleh pakai view langsung!)
// =====================================================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


// =====================================================================
// HALAMAN UMUM
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/tables', fn() => view('tables'))->name('tables');
    Route::get('/wallet', fn() => view('wallet'))->name('wallet');
    Route::get('/RTL', fn() => view('RTL'))->name('RTL');
    Route::get('/profile', fn() => view('account-pages.profile'))->name('profile');
});

Route::get('/signin', fn() => view('account-pages.signin'))->name('signin');
Route::get('/signup', fn() => view('account-pages.signup'))->name('signup')->middleware('guest');


// =====================================================================
// AUTHENTICATION
// =====================================================================
Route::get('/sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('sign-up');
Route::post('/sign-up', [RegisterController::class, 'store'])->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])->middleware('guest')->name('sign-in');
Route::post('/sign-in', [LoginController::class, 'store'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->middleware('guest');


// =====================================================================
// PROFIL & USER MANAGEMENT
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile');
    Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update');
    Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management');
});


// =====================================================================
// KAS (GLOBAL)
// =====================================================================
Route::prefix('kas')->name('kas.')->middleware('auth')->group(function () {
    Route::get('/', [KasController::class, 'index'])->name('index');
    Route::get('/create', [KasController::class, 'create'])->name('create');
    Route::post('/', [KasController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KasController::class, 'update'])->name('update');
    Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
});

Route::post('/kas/kelola-akun', [KasController::class, 'kelolaAkun'])->name('kas.kelolaAkun')->middleware('auth');
Route::get('/kas/reset-saldo', [KasController::class, 'resetSaldo'])->name('kas.resetSaldo')->middleware('auth');
Route::get('/kas/export/pdf', [KasController::class, 'exportPdf'])->name('kas.exportPdf')->middleware('auth');
Route::get('/kas/{id}', [KasController::class, 'show'])->name('kas.show')->middleware('auth');


// =====================================================================
// NERACA
// =====================================================================
Route::get('/neraca', [NeracaController::class, 'index'])
    ->name('neraca.index')
    ->middleware('auth');
Route::get('/neraca-tabel', [NeracaController::class, 'neracaTabel'])
    ->name('neraca.tabel');


// =====================================================================
// ASET
// =====================================================================
Route::resource('aset', AsetController::class)
    ->only(['index', 'create', 'store'])
    ->middleware('auth');


// =====================================================================
// KAMBING (TIDAK DIUBAH)
// =====================================================================
Route::prefix('kambing')->name('kambing.')->middleware('auth')->group(function () {

    Route::get('/', [KambingController::class, 'index'])->name('index');
    Route::get('/create', [KambingController::class, 'create'])->name('create');
    Route::post('/', [KambingController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KambingController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KambingController::class, 'update'])->name('update');
    Route::delete('/{id}', [KambingController::class, 'destroy'])->name('destroy');

    // Update berat
    Route::get('/{id}/update-berat', [KambingController::class, 'updateBerat'])->name('updateBerat');
    Route::post('/{id}/update-berat', [KambingController::class, 'storeBerat'])->name('storeBerat');

    // Kas kambing
    Route::get('/{id}/kas', [KambingController::class, 'showKas'])->name('showKas');
    Route::post('/{id}/kas', [KambingController::class, 'storeKas'])->name('storeKas');

    // Jual
    Route::get('/jual', [KambingController::class, 'showJualForm'])->name('jual');
    Route::post('/jual', [KambingController::class, 'prosesJual'])->name('jual.proses');

    // Riwayat transaksi
    Route::get('/transaksi-list', [KambingController::class, 'transaksiList'])->name('transaksiList');
});


// =====================================================================
// FARM PAGE (TIDAK DIUBAH, HANYA DIRAPIKAN)
// =====================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('farm', [FarmController::class,'index'])->name('farm.index');
    Route::get('farm/create', [FarmController::class,'create'])->name('farm.create');
    Route::post('farm/store', [FarmController::class,'store'])->name('farm.store');
    Route::get('farm/{id}', [FarmController::class,'show'])->name('farm.show');
    Route::get('farm/{id}/edit', [FarmController::class,'edit'])->name('farm.edit');
    Route::put('farm/{id}', [FarmController::class,'update'])->name('farm.update');
    Route::delete('farm/{id}', [FarmController::class,'destroy'])->name('farm.destroy');

    // Weight & Feed
    Route::post('farm/{id}/weight', [FarmController::class,'updateWeight'])->name('farm.updateWeight');
    Route::post('farm/{id}/feed', [FarmController::class,'addFeed'])->name('farm.addFeed');
    
});

// routes/web.php
Route::get('/neraca/laba-rugi', [App\Http\Controllers\LabaRugiController::class, 'index'])
    ->name('neraca.laba-rugi');



/*
|--------------------------------------------------------------------------
| NERACA - PENJUALAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // =========================
    // KELOLA PENJUALAN
    // =========================
    Route::get('/neraca/penjualan', [PenjualanController::class, 'index'])
        ->name('neraca.penjualan.index');

    Route::post('/neraca/penjualan/store', [PenjualanController::class, 'store'])
        ->name('neraca.penjualan.store');

    // =========================
    // RINCIAN KAMBING
    // =========================
    Route::get('/neraca/rincian-kambing', [RincianKambingController::class, 'index'])
        ->name('neraca.rincian-kambing.index');

    // HPP Kambing
    Route::post('/neraca/rincian-kambing/hpp/store', [RincianKambingController::class, 'storeHpp'])
        ->name('neraca.rincian-kambing.hpp.store');

    // Kambing Mati
    Route::post('/neraca/rincian-kambing/mati/store', [RincianKambingController::class, 'storeMati'])
        ->name('neraca.rincian-kambing.mati.store');

});
