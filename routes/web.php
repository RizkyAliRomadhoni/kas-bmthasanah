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


Route::middleware(['auth'])->group(function () {
    Route::get('/neraca/laba-rugi', [LabaRugiController::class, 'index'])->name('neraca.laba-rugi');
    // Tambahkan ini:
    Route::get('/neraca/laba-rugi/refresh', [LabaRugiController::class, 'refresh'])->name('neraca.laba-rugi.refresh');
});

Route::get('/neraca/laba-rugi', [LabaRugiController::class, 'index'])->name('neraca.laba-rugi');
Route::get('/neraca/laba-rugi/refresh', [LabaRugiController::class, 'refresh'])->name('neraca.laba-rugi.refresh');
Route::post('/neraca/laba-rugi/store-manual', [LabaRugiController::class, 'storeManual'])->name('neraca.laba-rugi.store-manual');

Route::get('/laba-rugi', [LabaRugiController::class, 'index'])->name('neraca.laba-rugi');
Route::post('/laba-rugi/save', [LabaRugiController::class, 'storeManual'])->name('neraca.laba-rugi.save');

/*
|--------------------------------------------------------------------------
| NERACA - PENJUALAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // =========================
    // KELOLA PENJUALAN
    // =========================
    // Menampilkan halaman (GET)
    Route::get('/neraca/penjualan', [PenjualanController::class, 'index'])
        ->name('neraca.penjualan.index');

    // Menyimpan data (POST) 
    // Saya samakan URL-nya menjadi /neraca/penjualan agar lebih rapi
    Route::post('/neraca/penjualan', [PenjualanController::class, 'store'])
        ->name('neraca.penjualan.store');
});

use App\Http\Controllers\PakanController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/pakan', [PakanController::class, 'index'])->name('pakan.index');
    Route::post('/akun/pakan/update', [PakanController::class, 'storeOrUpdate'])->name('pakan.update');
});


use App\Http\Controllers\KandangController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/kandang', [App\Http\Controllers\KandangController::class, 'index'])->name('kandang.index');
    Route::post('/akun/kandang/update', [App\Http\Controllers\KandangController::class, 'storeOrUpdate'])->name('kandang.update');
});


use App\Http\Controllers\PerlengkapanController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/perlengkapan', [App\Http\Controllers\PerlengkapanController::class, 'index'])->name('perlengkapan.index');
    Route::post('/akun/perlengkapan/update', [App\Http\Controllers\PerlengkapanController::class, 'storeOrUpdate'])->name('perlengkapan.update');
});

use App\Http\Controllers\OperasionalController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/operasional', [OperasionalController::class, 'index'])->name('operasional.index');
    Route::post('/akun/operasional/update', [OperasionalController::class, 'storeOrUpdate'])->name('operasional.update');
});

use App\Http\Controllers\HutangController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::post('/akun/hutang/update', [HutangController::class, 'storeOrUpdate'])->name('hutang.update');
});

use App\Http\Controllers\PiutangController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/piutang', [PiutangController::class, 'index'])->name('piutang.index');
    Route::post('/akun/piutang/update', [PiutangController::class, 'storeOrUpdate'])->name('piutang.update');
});

use App\Http\Controllers\UpahController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/upah', [UpahController::class, 'index'])->name('upah.index');
    Route::post('/akun/upah/update', [UpahController::class, 'storeOrUpdate'])->name('upah.update');
});

use App\Http\Controllers\KambingAkunController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun/kambing', [KambingAkunController::class, 'index'])->name('kambing-akun.index');
    Route::post('/akun/kambing/store', [KambingAkunController::class, 'storeDetail'])->name('kambing-akun.storeDetail');
    Route::get('/akun/kambing/delete/{id}', [KambingAkunController::class, 'destroyDetail'])->name('kambing-akun.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/neraca/laba-rugi', [LabaRugiController::class, 'index'])->name('neraca.laba-rugi');
});

use App\Http\Controllers\RincianKambingController;

Route::middleware(['auth'])->group(function () {
    Route::get('/neraca/rincian-kambing', [RincianKambingController::class, 'index'])->name('neraca.rincian-kambing.index');
    Route::post('/neraca/rincian-kambing/store-hpp', [RincianKambingController::class, 'storeHpp'])->name('rincian-kambing.storeHpp');
    Route::post('/neraca/rincian-kambing/store-mati', [RincianKambingController::class, 'storeMati'])->name('rincian-kambing.storeMati');
    Route::get('/neraca/rincian-kambing/del-hpp/{id}', [RincianKambingController::class, 'destroyHpp'])->name('rincian-kambing.deleteHpp');
    Route::get('/neraca/rincian-kambing/del-mati/{id}', [RincianKambingController::class, 'destroyMati'])->name('rincian-kambing.deleteMati');
});


use App\Http\Controllers\KambingRincianHppController;

Route::middleware(['auth'])->group(function () {
    Route::get('/rincian-hpp', [KambingRincianHppController::class, 'index'])->name('rincian-hpp.index');
    Route::post('/rincian-hpp/store', [KambingRincianHppController::class, 'store'])->name('rincian-hpp.store');
    Route::put('/rincian-hpp/update/{id}', [KambingRincianHppController::class, 'update'])->name('rincian-hpp.update-induk');
    Route::delete('/rincian-hpp/delete/{id}', [KambingRincianHppController::class, 'destroy'])->name('rincian-hpp.destroy');
    Route::post('/rincian-hpp/tambah-bulan', [KambingRincianHppController::class, 'tambahBulan'])->name('rincian-hpp.tambah-bulan');
    Route::post('/rincian-hpp/update-cell', [KambingRincianHppController::class, 'updateCell'])->name('rincian-hpp.update');
    Route::post('/rincian-hpp/update-summary', [KambingRincianHppController::class, 'updateSummary'])->name('rincian-hpp.update-summary');
    
    // ROUTE BARU UNTUK LABEL SIDEBAR
    Route::post('/rincian-hpp/add-label', [KambingRincianHppController::class, 'addSummaryLabel'])->name('rincian-hpp.add-label');
    Route::delete('/rincian-hpp/delete-label/{id}', [KambingRincianHppController::class, 'deleteSummaryLabel'])->name('rincian-hpp.delete-label');

        // ROUTE KHUSUS UPDATE SEL TABEL (AJAX)
    Route::post('/rincian-hpp/update-cell', [KambingRincianHppController::class, 'updateCell'])->name('rincian-hpp.update-cell');
    
    // ROUTE KHUSUS UPDATE SIDEBAR MANUAL (AJAX)
    Route::post('/rincian-hpp/update-summary', [KambingRincianHppController::class, 'updateSummary'])->name('rincian-hpp.update-summary');
    
});