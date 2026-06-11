<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembayaranIuranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PeriodeIuranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// BENDAHARA
Route::middleware(['auth', 'role:bendahara'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Siswa
    Route::resource('siswa', SiswaController::class)->except(['show']);

    // Periode Iuran
    Route::resource('periode-iuran', PeriodeIuranController::class)->except(['show']);

    // Pembayaran Iuran
    Route::resource('pembayaran-iuran', PembayaranIuranController::class)->except(['show', 'edit', 'update']);

    // Kegiatan
    Route::resource('kegiatan', KegiatanController::class)->except(['edit', 'update']);

    // Pengeluaran (nested di dalam kegiatan)
    Route::get('/kegiatan/{kegiatan}/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/kegiatan/{kegiatan}/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SISWA
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])->name('dashboard.siswa');
    Route::get('/laporan-kegiatan', [LaporanController::class, 'kegiatanPublic'])->name('laporan.kegiatan');
});

require __DIR__ . '/auth.php';
