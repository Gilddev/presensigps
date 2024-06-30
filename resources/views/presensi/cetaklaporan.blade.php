<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
    <style>
        @page { size: A4 }

        #title{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }
        .alamat{
            font-style: italic;
        }

        .tabeldatakaryawan{
            margin-top: 40px;
        }

        .tabeldatakaryawan td{
            padding: 5px;
        }

        .tabelpresensi{
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi th{
            border: 1px solid;
            padding: 8px;
        }
        .tabelpresensi td{
            font-size: 14px;
            border: 1px solid;
            padding: 8px;
        }
        .foto{
            width: 64px;
            height: 64px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

        <?php
        function selisih($jadwal_jam_masuk, $jam_kedatangan)
        {
            list($h, $m, $s) = explode(":", $jadwal_jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_kedatangan);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
        ?>

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <!-- Write HTML just like a web page -->
    <table style="width: 100%" >
        <tr>
            <td style="width: 40" >
                <img src="{{ asset('assets/img/logo_laporan.png') }}" width="70" height="70" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper($namabulan[(string)$bulan]) }} {{ $tahun }} <br>
                    RUMAH SAKIT IBU DAN ANAK SITTI KHADIJAH <br>
                </span>
                <span class="alamat">Jl. Nani Wartabone No. 101 Telp. (0435) 821253-824410 Email : rsia_gtlo@gmail.co.id</span>
            </td>
        </tr>
    </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td rowspan="5">
                    @php
                    $path = Storage::url('upload/karyawan/' . $karyawan -> foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="100px" height="100px">
                </td> 
            </tr>
            <tr>
                <td>Nik</td>
                <td>:</td>
                <td>{{ $karyawan -> nik }}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>:</td>
                <td>{{ $karyawan -> nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan -> jabatan }}</td>
            </tr>
            <tr>
                <td>Ruangan</td>
                <td>:</td>
                <td>{{ $karyawan -> nama_ruangan }}</td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Keluar</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Jam Kerja</th>
            </tr>
                @foreach ($presensi as $d)
                    @php
                    $path_foto_in = Storage::url('upload/absensi/' . $d -> foto_in);
                    $path_foto_out = Storage::url('upload/absensi/' . $d -> foto_out);
                    $jam_terlambat = selisih('08:00:00', $d -> jam_in);
                    @endphp
                    <tr>
                        <td>{{ $loop -> iteration }}</td>
                        <td>{{ date("d-m-Y", strtotime($d -> tgl_presensi)) }}</td>
                        <td>{{ $d -> jam_in }}</td>
                        <td><img src="{{ url($path_foto_in) }}" class="foto" alt=""></td>
                        <td>{{ $d -> jam_out != null ? $d -> jam_out : 'Belum Absen' }}</td>
                        <td>
                            @if ($d -> jam_out != null)
                                <img src="{{ url($path_foto_out) }}" class="foto" alt="">
                            @else
                                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="foto" alt="">
                            @endif
                        </td>
                        <td>
                            @if ($d -> jam_in > '08:00')
                                Terlambat {{ $jam_terlambat }}
                            @else
                                Tepat Waktu
                            @endif
                        </td>
                        <td>
                            @if ($d -> jam_out != null)
                                @php
                                    $jmljamkerja = selisih($d -> jam_in, $d -> jam_out);
                                @endphp
                            @else
                                @php
                                    $jmljamkerja = 0;
                                @endphp
                            @endif
                            {{ $jmljamkerja }}
                        </td>
                    </tr>
                @endforeach
        </table>

        <table width="100%" style="margin-top: 150px">
            <tr>
                <td></td>
                <td style="text-align: center">Gorontalo, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom" height="150px">
                    <u>Agil Dwi Sulistyo</u><br>
                    <i>HRD Manager</i>
                </td>
                <td style="text-align: center; vertical-align: bottom">
                    <u>Rusli A. Katili</u><br>
                    <i>Direktur</i>
                </td>
            </tr>
        </table>

  </section>

</body>

</html>