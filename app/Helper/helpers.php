<?php
function hitungjamterlambat($jadwal_jam_masuk, $jam_presensi){
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor($diffterlambat - ($jamterlambat * (60 * 60))) / 60;

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;

    $terlambat = $jterlambat . ":" . $mterlambat;
    return $terlambat;
}

function hitunghari($tanggal_mulai, $tanggal_akhir){
    $tanggal_1 = date_create($tanggal_mulai);
    $tanggal_2 = date_create($tanggal_akhir);
    $diff = date_diff($tanggal_1, $tanggal_2);

    return $diff->days + 1;

}

function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0){
    $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
    $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
    $kode = $kunci . $nomor_baru_plus_nol;
    return $kode;
}

