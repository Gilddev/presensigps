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
            font-size: 10px;
        }
        .tabelpresensi td{
            font-size: 8px;
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
<body class="A4 landscape">

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
                    REKAP PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper($namabulan[(string)$bulan]) }} {{ $tahun }} <br>
                    RUMAH SAKIT IBU DAN ANAK SITTI KHADIJAH <br>
                </span>
                <span class="alamat">Jl. Nani Wartabone No. 101 Telp. (0435) 821253-824410 Email : rsia_gtlo@gmail.co.id</span>
            </td>
        </tr>
    </table>
        
    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">Nik</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">TT</th>
        </tr>
        <tr>
            <?php
            for($i = 1; $i <= 31; $i++){
            ?>
                <th>{{ $i }}</th>
            <?php
            }
            ?>
        </tr>
        @foreach ($rekap as $d)
            <tr>
                <td>{{ $d->nik }}</td>
                <td>{{ $d->nama_lengkap }}</td>

                <?php
                    $totalhadir = 0;
                    $totalterlambat = 0;
                    for($i = 1; $i <= 31; $i++){
                    $tgl = "tgl_" . $i;
            
                    if(empty($d->$tgl)){
                        $hadir = ['',''];
                        $totalhadir += 0;
                    }else{
                        $hadir = explode("-", $d->$tgl);
                        $totalhadir += 1;
                        if($hadir[0] > "08:00:00"){
                            $totalterlambat += 1;
                        }
                    }
                ?>
                <td>
                    <span style="color: {{ $hadir[0] > "08:00:00" ? "red" : ""}}">{{ isset($hadir[0]) ? $hadir[0] : '0' }}<br></span>
                    <span style="color: {{ $hadir[1] < "16:30:00" ? "red" : ""}}">{{ isset($hadir[1]) ? $hadir[1] : '1' }}<br></span>
                </td>
                <?php
                    }
                ?>
                <td>{{ $totalhadir }}</td>
                <td>{{ $totalterlambat }}</td>
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