<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzinsakitController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\AbstractRouteCollection;

route::middleware(['guest:karyawan'])->group(function(){
    route::get('/', function(){
        return view('auth.login');
    })->name('login');
    route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

route::middleware(['guest:user'])->group(function(){
    route::get('/panel', function(){
        return view('auth.loginadmin');
    })->name('loginadmin');
    route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //edit profile
    Route::get('/editprofile', [PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    //histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class,'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class,'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::Class, 'cekpengajuanizin']);

    //izin absen
    Route::get('/izinabsen', [IzinabsenController::class, 'create']);
    Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);
    Route::get('/izinabsen/{kode_izin}/edit', [IzinabsenController::class, 'edit']);
    Route::post('/izinabsen/{kode_izin}/update', [IzinabsenController::class, 'update']);

    //izin sakit
    Route::get('/izinsakit', [IzinsakitController::class, 'create']);
    Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
    Route::get('/izinsakit/{kode_izin}/edit', [IzinsakitController::class, 'edit']);
    Route::post('/izinsakit/{kode_izin}/update', [IzinsakitController::class, 'update']);

    //edit pengajuan izin
    Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
    Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});

Route::middleware(['auth:user']) -> group(function(){
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);

    //karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);

    //ruangan
    Route::get('/ruangan', [RuanganController::class, 'index']);
    Route::post('/ruangan/store', [RuanganController::class, 'store']);
    Route::post('/ruangan/edit', [RuanganController::class, 'edit']);
    Route::post('/ruangan/{kode_ruangan}/update', [RuanganController::class, 'update']);
    Route::post('/ruangan/{kode_ruangan}/delete', [RuanganController::class, 'delete']);

    //presensi monitoring
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::Class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::Class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizinsakit', [PresensiController::Class, 'batalkanizinsakit']);

    //konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::Class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::Class, 'updatelokasikantor']);
    
    //konfigurasi jam kerja
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::Class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::Class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::Class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::Class, 'updatejamkerja']);
    Route::post('/konfigurasi/{kode_jam_kerja}/delete', [KonfigurasiController::Class, 'deletejamkerja']);
    Route::get('/konfigurasi/{nik}/setjamkerja', [KonfigurasiController::Class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::Class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::Class, 'updatesetjamkerja']);
});
