<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RuanganController extends Controller
{
    public function index(Request $request){
        $nama_ruangan = $request -> nama_ruangan;
        $query = Ruangan::query();
        $query -> select('*');
        if(!empty($nama_ruangan)){
            $query -> where('nama_ruangan', 'like', '%' . $nama_ruangan .'%');
        }

        $ruangan = $query -> get();

        //$ruangan = DB::table('ruangan') -> orderBy('kode_ruangan') -> get();
        return view('ruangan.index', compact('ruangan'));
    }

    public function store(Request $request){
        $kode_ruangan = $request -> kode_ruangan;
        $nama_ruangan = $request -> nama_ruangan;
        $data = [
            'kode_ruangan' => $kode_ruangan,
            'nama_ruangan' => $nama_ruangan
        ];

        $simpan = DB::table('ruangan') -> insert($data);
        if($simpan){
            return Redirect::back() -> with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back() -> with(['warning' => 'Data Gagal Disimpan']);           
        }
    }

    public function edit(Request $request){
        $kode_ruangan = $request -> kode_ruangan;
        $ruangan = DB::table('ruangan') -> where('kode_ruangan', $kode_ruangan) -> first();
        return view ('ruangan.edit', compact('ruangan'));
    }

    public function update($kode_ruangan, Request $request){
        $nama_ruangan = $request -> nama_ruangan;
        $data = [
            'nama_ruangan' => $nama_ruangan
        ];

        $update = DB::table('ruangan') -> where('kode_ruangan', $kode_ruangan) -> update($data);
        if($update){
            return Redirect::back() -> with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back() -> with(['warning' => 'Data Gagal Diupdate']);           
        }
    }

    public function delete($kode_ruangan){
        $hapus = DB::table('ruangan') -> where('kode_ruangan', $kode_ruangan) -> delete();
        if($hapus){
            return Redirect::back() -> with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back() -> with(['warning' => 'Data Gagal Dihapus']);           
        }
    }
}
