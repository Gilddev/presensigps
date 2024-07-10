<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index(Request $request) {

        // fungsi untuk pencarian sesuai nama dan ruangan di dashboard admin
        $query = Karyawan::query();
        $query -> select('karyawan.*', 'nama_ruangan');
        $query -> join('ruangan', 'karyawan.kode_ruangan', '=', 'ruangan.kode_ruangan');
        $query -> orderBy('nama_lengkap');
        if (!empty($request -> nama_karyawan)){
            $query -> where('nama_lengkap', 'like', '%' . $request -> nama_karyawan . '%');
        }

        if (!empty($request -> kode_ruangan)){
            $query -> where('karyawan.kode_ruangan', $request -> kode_ruangan);
        }

        $karyawan = $query -> paginate(10);

        $ruangan = DB::table('ruangan') -> get();
        return view('karyawan.index', compact('karyawan', 'ruangan'));
    }

    public function store(Request $request){
        $nik = $request -> nik;
        $nama_lengkap = $request -> nama_lengkap;
        $jabatan = $request -> jabatan;
        $kode_ruangan = $request -> kode_ruangan;
        $jenis_kelamin = $request -> jenis_kelamin;
        $alamat = $request -> alamat;
        $no_hp = $request -> no_hp;
        $password = Hash::make('12345');

        if($request -> hasFile('foto')){
            $foto = $nik.".".$request -> file('foto') -> getClientOriginalExtension();
        } else{
            $foto = null;
        }
        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'kode_ruangan' => $kode_ruangan,
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('karyawan') -> insert($data);
            if ($simpan) {
                if($request -> hasFile('foto')){
                    $folderPath = "public/upload/karyawan/";
                    $request -> file('foto') -> storeAs($folderPath, $foto);
                }
                return Redirect::back() -> with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
            if($e -> getCode() == 23000){
                $message = ", Data dengan Nik " . $nik . " sudah terdaftar";
            }
            return Redirect::back() -> with(['warning' => 'Data Gagal Disimpan' . $message]);
        }
    }

    public function edit(Request $request){
        $nik = $request -> nik;
        $ruangan = DB::table('ruangan') -> get();
        $karyawan = DB::table('karyawan') -> where('nik', $nik) ->first();

        return view('karyawan.edit', compact('ruangan', 'karyawan'));
    }

    public function update($nik, Request $request){
        $nik = $request -> nik;
        $nama_lengkap = $request -> nama_lengkap;
        $jabatan = $request -> jabatan;
        $kode_ruangan = $request -> kode_ruangan;
        $jenis_kelamin = $request -> jenis_kelamin;
        $alamat = $request -> alamat;
        $no_hp = $request -> no_hp;
        $password = Hash::make('12345');

        $old_foto = $request -> old_foto;

        if($request -> hasFile('foto')){
            $foto = $nik.".".$request -> file('foto') -> getClientOriginalExtension();
        } else{
            $foto = $old_foto;
        }
        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'kode_ruangan' => $kode_ruangan,
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('karyawan') -> where('nik', $nik) -> update($data);
            if ($update) {
                if($request -> hasFile('foto')){
                    $folderPath = "public/upload/karyawan/";
                    $folderPathOld = "public/upload/karyawan/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request -> file('foto') -> storeAs($folderPath, $foto);
                }
                return Redirect::back() -> with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back() -> with(['success' => 'Data Gagal Diupdate']);
        }        
    }

    public function delete($nik){
        $delete = DB::table('karyawan') -> where('nik', $nik) -> delete();
        if($delete){
            return Redirect::back() -> with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back() -> with(['warning' => 'Data Gagal Dihapus']);
        }
    }
}
