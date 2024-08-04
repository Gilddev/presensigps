<?php

namespace App\Http\Controllers;

use App\Models\Setjamkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\TryCatch;

class KonfigurasiController extends Controller
{
    public function lokasikantor(){
        $lok_kantor = DB::table('konfigurasi_lokasi') -> where('id', 1) -> first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request){
        $lokasi_kantor = $request -> lokasi_kantor;
        $radius = $request -> radius;

        $update = DB::table('konfigurasi_lokasi') -> where('id', 1) -> update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back() -> with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back() -> with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function jamkerja(){
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }

    public function storejamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;
        $lintashari = $request->lintashari;
        
        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang,
            'lintashari' => $lintashari
        ];
        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch  (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
    public function editjamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jam_kerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jam_kerja'));
    }

    public function updatejamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;
        $lintashari = $request->lintashari;
        
        $data = [
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang,
            'lintashari' => $lintashari
        ];
        try {
            DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch  (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function deletejamkerja($kode_jam_kerja){
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if  ($hapus){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }

    public function setjamkerja($nik){
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        $cek_jam_kerja = DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->count();
        //dd($cek_jam_kerja);
        if($cek_jam_kerja > 0){
            $set_jam_kerja = DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->get();
            return view('konfigurasi.editsetjamkerja', compact('karyawan', 'jam_kerja', 'set_jam_kerja'));
        }else{
            return view('konfigurasi.setjamkerja', compact('karyawan', 'jam_kerja'));
        }
    }

    public function storesetjamkerja(Request $request){
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        
        for($i=0; $i < count($hari); $i++){
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        //dd($data);
        try {
            Setjamkerja::insert($data);
            return Redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil Di Setting']);
        } catch (\Throwable $e) {
            //dd($e);
            return Redirect('/karyawan')->with(['warning' => 'Jam Kerja Gagal Di Setting']);
        }
    }

    public function updatesetjamkerja(Request $request){
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        
        for($i=0; $i < count($hari); $i++){
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        //dd($data);
        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return Redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil Di Update']);
        } catch (\Throwable $e) {
            //dd($e);
            DB::rollBack();
            return Redirect('/karyawan')->with(['warning' => 'Jam Kerja Gagal Di Update']);
        }
    }
}
