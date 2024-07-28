<style>
    #map { height: 320px; }
    #fotoin { height: 320px; }
    #fotoout { height: 320px; }
</style>

@php
    $defaultImage = 'assets/img/sample/avatar/default.jpg';

    if ($presensi && $presensi->foto_in) {
        $pathin = Storage::url('upload/absensi/' . $presensi -> foto_in);
    } else {
        // URL gambar default jika gambar tidak ada di database
        $pathin = asset($defaultImage);
    }

    if ($presensi && $presensi->foto_out) {
        $pathout = Storage::url('upload/absensi/' . $presensi -> foto_out);
    } else {
        // URL gambar default jika gambar tidak ada di database
        $pathout = asset($defaultImage);
    }
@endphp
Foto In
<div id="fotoin"><img src="{{ $pathin }}" alt="" height="100%"></div>
Foto Out
<div id="fotoout"><img src="{{ $pathout }}" alt="" height="100%"></div>
<br>
Lokasi Pengambilan Absensi
<div id="map"></div>

<script>
    var lokasi = "{{ $presensi -> lokasi_in }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitute = lok[1];
    var map = L.map('map').setView([latitude, longitute], 17);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([latitude, longitute]).addTo(map);

    var circle = L.circle([0.5393462384564144, 123.0614931258743], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 80
    }).addTo(map); 

    var popup = L.popup()
    .setLatLng([latitude, longitute])
    .setContent("{{ $presensi -> nama_lengkap }}")
    .openOn(map);
</script>