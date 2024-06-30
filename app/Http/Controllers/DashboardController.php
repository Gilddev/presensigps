<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date('m') * 1;
        $tahunini = date('Y');
        $nik = Auth::guard('karyawan') -> user() -> nik;
        $presensihariini = DB::table('presensi') -> where('nik', $nik) -> where('tgl_presensi', $hariini) -> first();

        // menampilkan histori pada dashboard
        $historibulanini = DB::table('presensi') -> whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            -> where('nik', $nik)
            -> whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            -> whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"') 
            -> orderBy('tgl_presensi')
            -> get();
        
        // menampilkan rekap presensi pada dashboard presensi karyawan
        $rekappresensi = DB::table('presensi') 
            -> selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00", 1, 0)) as jmlterlambat') 
            -> where('nik', $nik)
            -> whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            -> whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            -> first();
        
        // menampilkan leaderboard pada dashboard
        $leaderboard = DB::table('presensi')
            -> join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            -> where('tgl_presensi', $hariini)
            -> orderBy('jam_in')
            -> get();

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        // menampilkan jumlah data izin atau sakit di dashboard
        $rekapizin = DB::table('pengajuan_izin')
            -> selectRaw('SUM(IF(status = "i", 1, 0)) as jmlizin, SUM(IF(status = "s", 1, 0)) as jmlsakit')
            -> where('nik', $nik)
            -> whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            -> whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            -> where('status_approved', 1)
            -> first();

        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 
        'tahunini', 'rekappresensi', 'leaderboard', 'rekapizin'));
    }

    public function dashboardadmin(){
        // menampilkan rekap presensi pada dashboard administrator
        $hariini = date("Y-m'd");
        $rekappresensi = DB::table('presensi') 
            -> selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00", 1, 0)) as jmlterlambat')
            -> where('tgl_presensi', $hariini) 
            -> first();

        // menampilkan jumlah data izin atau sakit di dashboard
        $rekapizin = DB::table('pengajuan_izin')
            -> selectRaw('SUM(IF(status = "i", 1, 0)) as jmlizin, SUM(IF(status = "s", 1, 0)) as jmlsakit')
            -> where('tgl_izin', $hariini) 
            -> where('status_approved', 1)
            -> first();

        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));

        
    }
}
