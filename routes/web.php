<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\admin\GuruController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\LiburController;
use App\Http\Controllers\admin\SiswaController;

use App\Http\Controllers\operator\OperatorController;
use App\Http\Controllers\admin\RekapAbsensiController;
use App\Http\Controllers\admin\WaktuAbsensiController;
use App\Http\Controllers\Operator\AbsensiScanController;

use App\Http\Controllers\admin\RekapAbsensiGuruController;
use App\Http\Controllers\admin\AdminController as AdminController;
use App\Http\Controllers\admin\KelasController as adminKelasController;
use App\Http\Controllers\Operator\KelasController as OperatorKelasController;
use App\Http\Controllers\Operator\WaktuAbsensiController as OperatorWaktuAbsensiController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SesiController::class, 'index']);
Route::post('/', [SesiController::class, 'login'])->name('login');
Route::get('/logout', [SesiController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'cek.role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/master', [AdminController::class, 'master'])->name('admin.master.index');

    Route::get('/admin/password/edit', [OperatorController::class, 'editPassword'])->name('admin.password.edit');
    Route::post('/admin/password/update', [OperatorController::class, 'updatePassword'])->name('admin.password.update');

    Route::get('/admin/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/admin/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/admin/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('kelas.index');
    Route::post('/admin/kelas', [AdminKelasController::class, 'store'])->name('kelas.store');
    Route::put('/admin/kelas/{id}', [AdminKelasController::class, 'update'])->name('kelas.update');
    Route::delete('/admin/kelas/{id}', [AdminKelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('admin/kelas/{id}', [AdminKelasController::class, 'show'])->name('kelas.show');

    Route::get('/admin/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/admin/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::get('/admin/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::post('/admin/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/admin/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/admin/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/admin/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');

    Route::get('/admin/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/admin/guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::get('/admin/guru/{id}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::post('/admin/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::put('/admin/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/admin/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::get('/admin/guru/{id}', [GuruController::class, 'show'])->name('guru.show');


    Route::get('/admin/libur', [LiburController::class, 'index'])->name('admin.libur.index');
    Route::post('/admin/libur', [LiburController::class, 'store'])->name('libur.store');
    Route::put('/admin/libur/{libur}', [LiburController::class, 'update'])->name('libur.update');
    Route::delete('/admin/libur/{libur}', [LiburController::class, 'destroy'])->name('libur.destroy');

    Route::get('/admin/waktu-absensi', [WaktuAbsensiController::class, 'index'])->name('admin.WaktuAbsensi.index');
    Route::post('/admin/waktu-absensi/update-all', [WaktuAbsensiController::class, 'updateAll'])->name('waktu_absensi.update_all');
    Route::delete('/admin/waktu-absensi/reset', [WaktuAbsensiController::class, 'reset'])->name('waktu_absensi.reset');

    Route::get('/admin/rekap-harian', [RekapAbsensiController::class, 'harian'])->name('rekap.harian');
    Route::get('/admin/rekap-mingguan', [RekapAbsensiController::class, 'mingguan'])->name('rekap.mingguan');
    Route::get('/admin/rekap-bulanan', [RekapAbsensiController::class, 'bulanan'])->name('rekap.bulanan');
    Route::post('/admin/rekap/bulanan/update-status', [RekapAbsensiController::class, 'updateStatus'])->name('rekap.bulanan.updateStatus');
    Route::get('/admin/rekap/bulanan/export/{format}', [RekapAbsensiController::class, 'exportBulanan'])->name('rekap.bulanan.export');

    Route::get('/admin/rekap/guru/harian', [RekapAbsensiGuruController::class, 'harianGuru'])->name('rekap.guru.harian');

    Route::get('/admin/rekap/guru/bulanan', [RekapAbsensiGuruController::class, 'bulananGuru'])->name('rekap.guru.bulanan');
    Route::post('/admin/rekap/guru/bulanan/update-status', [RekapAbsensiGuruController::class, 'updateStatus'])->name('rekap.guru.bulanan.updateStatus');
    Route::get('/admin/rekap/guru/bulanan/export/{format}', [RekapAbsensiGuruController::class, 'exportBulanan'])->name('rekap.guru.bulanan.export');
});

Route::middleware(['auth', 'cek.role:operator'])->group(function () {
    Route::get('/operator', [OperatorController::class, 'index'])->name('operator.dashboard');

    Route::get('/operator/password/edit', [OperatorController::class, 'editPassword'])->name('operator.password.edit');
    Route::post('/operator/password/update', [OperatorController::class, 'updatePassword'])->name('operator.password.update');


    Route::get('/operator/kelas', [OperatorKelasController::class, 'index'])->name('operator.kelas.index');
    Route::get('/operator/kelas/{id}/siswa', [OperatorKelasController::class, 'show'])->name('operator.kelas.siswa');
    Route::get('/operator/waktu-absensi', [OperatorWaktuAbsensiController::class, 'index'])->name('operator.waktu.index');

    Route::get('/operator/absensi', [AbsensiScanController::class, 'index'])->name('operator.absensi.index');
    Route::get('/operator/absensi2', [AbsensiScanController::class, 'index2'])->name('operator.absensi.index2');
    Route::post('/operator/absensi', [AbsensiScanController::class, 'store'])->name('operator.absensi.store');

    Route::get('/operator/absensi/siswa', [AbsensiScanController::class, 'showAbsenSiswa'])->name('operator.absensi.siswa');
    Route::get('/operator/absensi/guru', [AbsensiScanController::class, 'showAbsenGuru'])->name('operator.absensi.guru');

    Route::post('/operator/absensi/siswa/update', [AbsensiScanController::class, 'updateAbsenSiswa'])->name('operator.absensi.siswa.update');
    Route::post('/operator/absensi/guru/update', [AbsensiScanController::class, 'updateAbsenGuru'])->name('operator.absensi.guru.update');
});
