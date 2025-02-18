<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

use App\Models\Pengajuanizin;

class PresensiController extends Controller
{
    public function gethari($hari){
        // $hari = date("D");
        switch($hari){
            case 'Sun':
                $hari_ini = "Minggu";
                break;
            case 'Mon':
                $hari_ini = "Senin";
                break;
            case 'Tue':
                $hari_ini = "Selasa";
                break;
            case 'Wed':
                $hari_ini = "Rabu";
                break;
            case 'Thu':
                $hari_ini = "Kamis";
                break;
            case 'Fri':
                $hari_ini = "Jumat";
                break;
            case 'Sat':
                $hari_ini = "Sabtu";
                break;
        }
        return $hari_ini;
    }

    public function create(){
        $nik = Auth::guard('karyawan')->user()->nik;
        $hariini = date("Y-m-d");

        $jamsekarang = date("H:i");
        $tgl_sebelumnya = date("Y-m-d", strtotime("-1 days", strtotime($hariini)));
        $cek_presensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();
        
        $cek_lintas_hari_presensi = $cek_presensi_sebelumnya != null ? $cek_presensi_sebelumnya->lintashari : 0;

        if ($cek_lintas_hari_presensi == 1){
            if ($jamsekarang < "08:00"){
                $hariini = $tgl_sebelumnya;
                }
            }

        $namahari = $this->gethari(date('D', strtotime($hariini)));
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $cekkondisi = DB::table('presensi')->select('nik', 'jam_in', 'jam_out')->where('tgl_presensi', $hariini)->where('nik', $nik)->get();
        $lok_kantor = DB::table('konfigurasi_lokasi') -> where('id', 1) -> first();
        $jamkerja = DB::table('konfigurasi_jam_kerja')
            ->join('jam_kerja', 'konfigurasi_jam_kerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)->where('hari', $namahari)->first();
        
        if($jamkerja == null){
            return view('presensi.notifjadwal');
        }else{
            return view('presensi.create', compact('cek', 'lok_kantor', 'cekkondisi','jamkerja', 'hariini'));
        }
    }

    public function store(Request $request){

        $nik = Auth::guard('karyawan')->user()->nik;
        $hariini = date("Y-m-d");
        $jamsekarang = date("H:i");
        $tgl_sebelumnya = date("Y-m-d", strtotime("-1 days", strtotime($hariini)));
        $cek_presensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $cek_lintas_hari_presensi = $cek_presensi_sebelumnya != null ? $cek_presensi_sebelumnya->lintashari : 0;

        $tgl_presensi = $cek_lintas_hari_presensi == 1 && $jamsekarang < "08:00" ? $tgl_sebelumnya : date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi') -> where('id', 1) -> first();
        $lok = explode(",", $lok_kantor -> lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request -> lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this -> distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        //cek jam kerja karyawan
        $namahari = $this->gethari(date('D', strtotime($tgl_presensi)));
        $jamkerja = DB::table('konfigurasi_jam_kerja')
            ->join('jam_kerja', 'konfigurasi_jam_kerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)->where('hari', $namahari)->first();
 
        //dd($jamkerja);
        // $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        $presensi = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();

        if($cek > 0){
            $ket = "out";
        }else{
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/upload/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
        
        $tgl_pulang = $jamkerja->lintashari == 1 ? date('Y-m-d', strtotime("+ 1 days", strtotime($tgl_presensi))) : $tgl_presensi;
        $jam_pulang = $hariini . " " . $jam;
        $jam_kerja_pulang = $tgl_pulang . " " . $jamkerja->jam_pulang;
        //$datakaryawan = DB::table('karyawan')->where('nik', $nik)->first();
        //dd($jam_pulang, $jam_kerja_pulang);
        if($radius >= $lok_kantor -> radius){
            echo("error|Maaf anda di luar radius, jarak anda " . $radius . " meter dari kantor|radius");
        }else {
            if($cek > 0){
                // if($jam < $jamkerja->jam_pulang){
                if($jam_pulang < $jam_kerja_pulang){
                    echo ("error|Maaf Belum Waktunya Pulang|out");
                }else{
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if($update){
                        echo ("success|Terimakasih, Hati-hati Di Jalan|out");
                        Storage::put($file, $image_base64);
                    }else{
                        echo ("error|Gagal Absen, Hubungi Staff IT|out");
                    }
                }
            }else{
                if($jam < $jamkerja->awal_jam_masuk){
                    echo ("error|Maaf, Belum Waktunya Melakukan Absensi|in");
                }else if($jam > $jamkerja->akhir_jam_masuk){
                    echo ("error|Maaf, Waktu Melakukan Absensi Sudah Habis|in");
                }else{
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h'
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if($simpan){
                        //dd($dinas);
                        echo ("success|Terimakasih, Selamat Bekerja|in");
                        Storage::put($file, $image_base64);
                    }else{
                        echo ("error|Gagal Absen, Hubungi Staff IT|in");
                    }
                }
            }
        }
        
    }
    
    // menghitung jarak user dari radius lokasi kantor
    function distance($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
    
    public function editprofile(){
        $nik = Auth::guard('karyawan') -> user() -> nik;
        $karyawan = DB::table('karyawan') -> where('nik', $nik) -> first();
        //dd($karyawan);
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request){
        $nik = Auth::guard('karyawan') -> user() -> nik;
        $nama_lengkap = $request -> nama_lengkap;
        $jabatan = $request -> jabatan;
        $no_hp = $request -> no_hp;
        $password = Hash::make($request -> password);
        $karyawan = DB::table('karyawan') -> where('nik', $nik) -> first();
        $request->validate([
            'foto' => 'required|image|mimes:png,jpg|max:3000'
        ]);
        if($request -> hasFile('foto')){
            $foto = $nik.".".$request -> file('foto') -> getClientOriginalExtension();
        } else{
            $foto = $karyawan -> foto;
        }
        if(empty($request -> password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else{
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
        }
        $update = DB::table('karyawan') -> where('nik', $nik) -> update($data);
        if($update){
            if($request -> hasFile('foto')){
                $folderPath = "public/upload/karyawan/";
                $request -> file('foto') -> storeAs($folderPath, $foto);
            }
            return Redirect::back() -> with(['success' => 'Data Berhasil Di Update']);
        } else{
            return Redirect::back() -> with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function histori(){
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request){
        $bulan = $request -> bulan;
        $tahun = $request -> tahun;
        $nik = Auth::guard('karyawan') -> user() -> nik;

        $histori = DB::table('presensi')
            -> whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            -> whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            -> where('nik', $nik)
            -> orderBy('tgl_presensi')
            -> get();

            //dd($histori);
        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(Request $request){
        $nik = Auth::guard('karyawan') -> user() -> nik;

        if (!empty($request->bulan) && !empty($request->tahun)){
            $dataizin = DB::table('pengajuan_izin')
            -> orderBy('tgl_izin_dari', 'desc')
            -> where ('nik', $nik) 
            ->whereRaw('MONTH(tgl_izin_dari)="' . $request->bulan . '"')
            ->whereRaw('YEAR(tgl_izin_dari)="' . $request->tahun . '"')
            -> get();
        }else{
            $dataizin = DB::table('pengajuan_izin')
                -> orderBy('tgl_izin_dari', 'desc')
                -> where ('nik', $nik)
                -> limit(5)
                -> orderBy('tgl_izin_dari', 'desc')
                ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.izin', compact('dataizin', 'namabulan'));
    }

    public function buatizin(){
        
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request){
        $nik = Auth::guard('karyawan') -> user() -> nik;
        $tgl_izin = $request -> tgl_izin;
        $status = $request -> status;
        $keterangan = $request -> keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin') -> insert($data);

        if($simpan){
            return redirect('/presensi/izin') -> with(['success' => 'Data Berhasil Disimpan']);
        } else{
            return redirect('/presensi/izin') -> with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function monitoring(){
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request){
        $tanggal = $request -> tanggal;
        $presensi = DB::table('presensi') 
            -> select('presensi.*', 'nama_lengkap', 'nama_ruangan', 'jam_masuk', 'nama_jam_kerja')
            -> leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            -> join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            -> join('ruangan', 'karyawan.kode_ruangan', '=', 'ruangan.kode_ruangan')
            -> where('tgl_presensi', $tanggal)
            -> get();
        
        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request){
        $id = $request -> id;
        $presensi = DB::table('presensi') -> where('id', $id) 
            -> join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            -> first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan(){
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan') -> orderBy('nama_lengkap') -> get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request){
        $nik = $request -> nik;
        $bulan = $request -> bulan;
        $tahun = $request -> tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember"];
        $karyawan = DB::table("karyawan") -> where('nik', $nik) 
            -> join('ruangan', 'karyawan.kode_ruangan', '=', 'ruangan.kode_ruangan')
            -> first();

        $presensi = DB::table('presensi')
            -> leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            -> where('nik', $nik)
            -> whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            -> whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            -> orderBy('tgl_presensi')
            -> get();

        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap(){
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request){
        $bulan = $request -> bulan;
        $tahun = $request -> tahun;
        $dari =  $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember"];

        while (strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[] = $dari;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }
        
        $jmlhari = count($rangetanggal);
        $lastrange = $jmlhari - 1;
        $sampai =  $rangetanggal[$lastrange];
        if($jmlhari == 30){
            array_push($rangetanggal, NULL);
        }elseif($jmlhari == 29){
            array_push($rangetanggal, NULL, NULL);
        }elseif($jmlhari == 28){
            array_push($rangetanggal, NULL, NULL, NULL);
        }

        $query = Karyawan::query();
        $query->selectRaw(
            "karyawan.nik, nama_lengkap, jabatan, kode_ruangan, 
            tgl_1,
            tgl_2,
            tgl_3,
            tgl_4,
            tgl_5,
            tgl_6,
            tgl_7,
            tgl_8,
            tgl_9,
            tgl_10,
            tgl_11,
            tgl_12,
            tgl_13,
            tgl_14,
            tgl_15,
            tgl_16,
            tgl_17,
            tgl_18,
            tgl_19,
            tgl_20,
            tgl_21,
            tgl_22,
            tgl_23,
            tgl_24,
            tgl_25,
            tgl_26,
            tgl_27,
            tgl_28,
            tgl_29,
            tgl_30,
            tgl_31"
        );

        $query->leftJoin(
            DB::raw("(
            SELECT presensi.nik,
	
            MAX(IF(tgl_presensi = '$rangetanggal[0]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_1,
            
            MAX(IF(tgl_presensi = '$rangetanggal[1]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_2,
            
            MAX(IF(tgl_presensi = '$rangetanggal[2]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_3,
            
            MAX(IF(tgl_presensi = '$rangetanggal[3]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_4,
            
            MAX(IF(tgl_presensi = '$rangetanggal[4]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_5,
            
            MAX(IF(tgl_presensi = '$rangetanggal[5]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_6,
            
            MAX(IF(tgl_presensi = '$rangetanggal[6]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_7,
            
            MAX(IF(tgl_presensi = '$rangetanggal[7]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_8,
            
            MAX(IF(tgl_presensi = '$rangetanggal[8]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_9,
            
            MAX(IF(tgl_presensi = '$rangetanggal[9]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_10,
            
            MAX(IF(tgl_presensi = '$rangetanggal[10]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_11,
            
            MAX(IF(tgl_presensi = '$rangetanggal[11]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_12,
            
            MAX(IF(tgl_presensi = '$rangetanggal[12]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_13,
            
            MAX(IF(tgl_presensi = '$rangetanggal[13]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_14,
            
            MAX(IF(tgl_presensi = '$rangetanggal[14]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_15,
            
            MAX(IF(tgl_presensi = '$rangetanggal[15]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_16,
            
            MAX(IF(tgl_presensi = '$rangetanggal[16]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_17,
            
            MAX(IF(tgl_presensi = '$rangetanggal[17]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_18,
            
            MAX(IF(tgl_presensi = '$rangetanggal[18]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_19,
            
            MAX(IF(tgl_presensi = '$rangetanggal[19]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_20,
            
            MAX(IF(tgl_presensi = '$rangetanggal[20]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_21,
            
            MAX(IF(tgl_presensi = '$rangetanggal[21]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_22,
            
            MAX(IF(tgl_presensi = '$rangetanggal[22]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_23,
            
            MAX(IF(tgl_presensi = '$rangetanggal[23]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_24,
            
            MAX(IF(tgl_presensi = '$rangetanggal[24]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_25,
            
            MAX(IF(tgl_presensi = '$rangetanggal[25]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_26,
            
            MAX(IF(tgl_presensi = '$rangetanggal[26]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_27,
            
            MAX(IF(tgl_presensi = '$rangetanggal[27]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_28,
            
            MAX(IF(tgl_presensi = '$rangetanggal[28]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_29,
            
            MAX(IF(tgl_presensi = '$rangetanggal[29]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_30,
            
            MAX(IF(tgl_presensi = '$rangetanggal[30]', CONCAT(
                IFNULL (jam_in, 'NA'), '|',
                IFNULL (jam_out, 'NA'), '|',
                IFNULL (presensi.STATUS, 'NA'), '|',
                IFNULL (nama_jam_kerja, 'NA'), '|',
                IFNULL (jam_masuk, 'NA'), '|',
                IFNULL (jam_pulang, 'NA'), '|',
                IFNULL (presensi.kode_izin, 'NA'), '|',
                IFNULL (keterangan, 'NA'), '|'
            ),NULL)) as tgl_31
            
            FROM presensi
            LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
            LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
            WHERE tgl_presensi Between '$rangetanggal[0]' AND '$sampai'
            GROUP BY nik
            ) presensi"),
             function($join){
                $join->on('karyawan.nik', '=', 'presensi.nik');
             }
        );

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();

        // dd($rekap);

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'rekap', 'namabulan', 'rangetanggal', 'jmlhari'));
    }

    public function izinsakit(Request $request){
        $query = Pengajuanizin::query();
        $query -> select('kode_izin', 'tgl_izin_dari', 'tgl_izin_sampai', 'pengajuan_izin.nik', 'nama_lengkap', 'kode_ruangan', 'status', 'status_approved', 'keterangan');
        $query -> join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');
        if(!empty($request -> dari) && !empty($request -> sampai)){
            $query -> whereBetween('tgl_izin_dari', [$request -> dari, $request -> sampai]);
        }

        if(!empty($request -> nik)){
            $query -> where('pengajuan_izin.nik', $request -> nik);
        }

        if(!empty($request -> nama_lengkap)){
            $query -> where('nama_lengkap', 'like', '%' . $request -> nama_lengkap . '%');
        }

        if($request -> status_approved === '0' || $request -> status_approved === '1' || $request -> status_approved === '2'){
            $query -> where('status_approved', $request -> status_approved);
        }

        $query -> orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query -> paginate(10);
        $izinsakit -> appends($request -> all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request){
        $status_approved = $request -> status_approved;
        $kode_izin = $request -> kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $nik = $dataizin->nik;
        $status = $dataizin->status;
        //dd($dataizin);
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {
                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+ 1 days", strtotime($tgl_dari)));
                }
            }
            DB::table('pengajuan_izin') -> where('kode_izin', $kode_izin) -> update(['status_approved' => $status_approved]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses']);
        }

        // $update = DB::table('pengajuan_izin') -> where('id', $kode_izin) -> update([
        //     'status_approved' => $status_approve
        // ]);
        // if($update){
        //     return Redirect::back() -> with(['success' => 'Data Berhasil Di Update']);
        // }else{
        //     return Redirect::back() -> with(['warning' => 'Data Gagal Di Update']);
        // }
    }

    public function batalkanizinsakit($kode_izin){
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin') -> where('kode_izin', $kode_izin) -> update([
                'status_approved' => 0
            ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Batalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Di Batalkan']);
        }
        // $update = DB::table('pengajuan_izin') -> where('kode_izin', $kode_izin) -> update([
        //     'status_approved' => 0
        // ]);
        if($update){
            return Redirect::back() -> with(['success' => 'Data Berhasil Di Update']);
        }else{
            return Redirect::back() -> with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function cekpengajuanizin(Request $request){
        $tgl_izin = $request -> tgl_izin;
        $nik = Auth::guard('karyawan') -> user() -> nik;
        $cek = DB::table('pengajuan_izin') -> where('nik', $nik) -> where('tgl_izin', $tgl_izin) -> count();
        return $cek;
    }

    public function showact($kode_izin){
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first(); 
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin){
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $file_surat_izin = $cekdataizin->file_surat_izin;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($file_surat_izin != null){
                Storage::delete('/public/upload/sid/' . $file_surat_izin);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Di Hapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['success' => 'Data Gagal Di Hapus']);
        }
    }
}
