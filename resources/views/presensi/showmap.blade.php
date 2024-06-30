<style>
    #map { height: 320px; }
</style>
<!-- {{ $presensi -> lokasi_in }} -->
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