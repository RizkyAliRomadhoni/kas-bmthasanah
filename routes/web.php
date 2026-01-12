<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
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
use App\Http\Controllers\PakanController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\UpahController;
use App\Http\Controllers\KambingAkunController;
use App\Http\Controllers\RincianKambingController;
use App\Http\Controllers\KambingRincianHppController;

// =====================================================================
// 1. PUBLIC ROUTES (GUEST)
// =====================================================================
Route::middleware('guest')->group(function () {
    // Redirect root ke sign-in jika belum login
    Route::get('/', function () {
        return redirect()->route('sign-in');
    });

    // Login Routes
    Route::get('/sign-in', [LoginController::class, 'create'])->name('sign-in');
    Route::post('/sign-in', [LoginController::class, 'store']);

    // Password Recovery (Opsional, tetap disediakan jika lupa password utama)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);

    // FITUR SIGN UP / REGISTER DIHAPUS UNTUK SISTEM TERKUNCI (LOCKED)
});


// =====================================================================
// 2. PROTECTED ROUTES (HANYA USER LOGIN)
// =====================================================================
Route::middleware('auth')->group(function () {

    // DASHBOARD & UTAMA
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // NERACA & KEUANGAN
    Route::prefix('neraca')->name('neraca.')->group(function () {
        Route::get('/', [NeracaController::class, 'index'])->name('index');
        Route::get('/tabel', [NeracaController::class, 'neracaTabel'])->name('tabel');
        Route::post('/add-account', [NeracaController::class, 'addAccount'])->name('add-account');
        
        // LABA RUGI
        Route::get('/laba-rugi', [LabaRugiController::class, 'index'])->name('laba-rugi');
        Route::get('/laba-rugi/refresh', [LabaRugiController::class, 'refresh'])->name('laba-rugi.refresh');
        Route::post('/laba-rugi/store-manual', [LabaRugiController::class, 'storeManual'])->name('laba-rugi.store-manual');
        Route::post('/laba-rugi/save', [LabaRugiController::class, 'storeManual'])->name('laba-rugi.save');

        // PENJUALAN
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

        // RINCIAN KAMBING (MODUL LAMA)
        Route::get('/rincian-kambing', [RincianKambingController::class, 'index'])->name('rincian-kambing.index');
        Route::post('/rincian-kambing/store-hpp', [RincianKambingController::class, 'storeHpp'])->name('rincian-kambing.storeHpp');
        Route::post('/rincian-kambing/store-mati', [RincianKambingController::class, 'storeMati'])->name('rincian-kambing.storeMati');
        Route::get('/rincian-kambing/del-hpp/{id}', [RincianKambingController::class, 'destroyHpp'])->name('rincian-kambing.deleteHpp');
        Route::get('/rincian-kambing/del-mati/{id}', [RincianKambingController::class, 'destroyMati'])->name('rincian-kambing.deleteMati');
    });

    // KAS (GLOBAL)
    Route::prefix('kas')->name('kas.')->group(function () {
        Route::get('/', [KasController::class, 'index'])->name('index');
        Route::get('/create', [KasController::class, 'create'])->name('create');
        Route::post('/', [KasController::class, 'store'])->name('store');
        Route::get('/reset-saldo', [KasController::class, 'resetSaldo'])->name('resetSaldo');
        Route::get('/export/pdf', [KasController::class, 'exportPdf'])->name('exportPdf');
        Route::post('/kelola-akun', [KasController::class, 'kelolaAkun'])->name('kelolaAkun');
        Route::get('/{id}', [KasController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [KasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KasController::class, 'update'])->name('update');
        Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
    });

    // MODUL BARU: RINCIAN HPP & STOK DINAMIS (EXCEL MODE)
    Route::prefix('rincian-hpp')->name('rincian-hpp.')->group(function () {
        Route::get('/', [KambingRincianHppController::class, 'index'])->name('index');
        Route::post('/store', [KambingRincianHppController::class, 'store'])->name('store');
        Route::put('/update/{id}', [KambingRincianHppController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [KambingRincianHppController::class, 'destroy'])->name('destroy');
        Route::post('/tambah-bulan', [KambingRincianHppController::class, 'tambahBulan'])->name('tambah-bulan');
        Route::post('/update-cell', [KambingRincianHppController::class, 'updateCell'])->name('update-cell');
        Route::post('/update-summary', [KambingRincianHppController::class, 'updateSummary'])->name('update-summary');
        Route::post('/add-label', [KambingRincianHppController::class, 'addSummaryLabel'])->name('add-label');
        Route::delete('/delete-label/{id}', [KambingRincianHppController::class, 'deleteSummaryLabel'])->name('delete-label');
        Route::post('/rincian-hpp/split/{id}', [KambingRincianHppController::class, 'splitRow'])->name('rincian-hpp.split');
    });


    // KELOLA AKUN OPERASIONAL (SUB-MENU)
    Route::prefix('akun')->group(function () {
        // Pakan
        Route::get('/pakan', [PakanController::class, 'index'])->name('pakan.index');
        Route::post('/pakan/update', [PakanController::class, 'storeOrUpdate'])->name('pakan.update');
        // Kandang
        Route::get('/kandang', [KandangController::class, 'index'])->name('kandang.index');
        Route::post('/kandang/update', [KandangController::class, 'storeOrUpdate'])->name('kandang.update');
        // Perlengkapan
        Route::get('/perlengkapan', [PerlengkapanController::class, 'index'])->name('perlengkapan.index');
        Route::post('/perlengkapan/update', [PerlengkapanController::class, 'storeOrUpdate'])->name('perlengkapan.update');
        // Operasional
        Route::get('/operasional', [OperasionalController::class, 'index'])->name('operasional.index');
        Route::post('/operasional/update', [OperasionalController::class, 'storeOrUpdate'])->name('operasional.update');
        // Hutang
        Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
        Route::post('/hutang/update', [HutangController::class, 'storeOrUpdate'])->name('hutang.update');
        // Piutang
        Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
        Route::post('/piutang/update', [PiutangController::class, 'storeOrUpdate'])->name('piutang.update');
        // Upah
        Route::get('/upah', [UpahController::class, 'index'])->name('upah.index');
        Route::post('/upah/update', [UpahController::class, 'storeOrUpdate'])->name('upah.update');
        // Stok Kambing (V1)
        Route::get('/kambing', [KambingAkunController::class, 'index'])->name('kambing-akun.index');
        Route::post('/kambing/store', [KambingAkunController::class, 'storeDetail'])->name('kambing-akun.storeDetail');
        Route::get('/kambing/delete/{id}', [KambingAkunController::class, 'destroyDetail'])->name('kambing-akun.destroy');
    });

    // MODUL KAMBING & FARM (TIDAK DIUBAH)
    Route::resource('aset', AsetController::class)->only(['index', 'create', 'store']);
    Route::resource('kambing', KambingController::class)->names('kambing');
    Route::get('/kambing-transaksi-list', [KambingController::class, 'transaksiList'])->name('kambing.transaksiList');
    
    Route::resource('farm', FarmController::class)->names('farm');
    Route::post('farm/{id}/weight', [FarmController::class,'updateWeight'])->name('farm.updateWeight');
    Route::post('farm/{id}/feed', [FarmController::class,'addFeed'])->name('farm.addFeed');

    // USER PROFILE & ACCOUNT PAGES
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/user-profile', [ProfileController::class, 'index'])->name('users.profile');
    Route::put('/user-profile/update', [ProfileController::class, 'update'])->name('users.update');
    Route::get('/users-management', [UserController::class, 'index'])->name('users-management');
    
    // TEMPLATE PAGES (KEEP IF NEEDED)
    Route::get('/tables', fn() => view('tables'))->name('tables');
    Route::get('/wallet', fn() => view('wallet'))->name('wallet');
});