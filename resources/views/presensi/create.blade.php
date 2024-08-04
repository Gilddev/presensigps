@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .webcam-capture, .webcam-capture video{
            display: block;
            width: 100% !important;
            height: auto !important;
            margin: auto;
            border-radius: 15px;
            top: 50px;
        }
        #map { 
            height: 230px; 
        }
        .jam-digital-malasngoding {
    
            background-color: #27272783;
            position: absolute;
            top: 65px;
            right: 15px;
            z-index: 9999;
            width: 100px;
            border-radius: 7px;
            padding: 5px;
        }
        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 13px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>

@endsection

@section('content')

<div class="row" style="margin-top: 60px">
     <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
     </div>
</div>

<div class="jam-digital-malasngoding">
    <p>{{ $hariini }}</p>
    <p id="jam"></p>
    <p>{{ $jamkerja->nama_jam_kerja }}</p>
    <p>Mulai : {{ date("H:i",strtotime($jamkerja->awal_jam_masuk)) }}</p>
    <p>Masuk : {{ date("H:i",strtotime($jamkerja->jam_masuk)) }}</p>
    <p>Akhir : {{ date("H:i",strtotime($jamkerja->akhir_jam_masuk)) }}</p>
    <p>Pulang : {{ date("H:i",strtotime($jamkerja->jam_pulang)) }}</p>
</div>

<div class="row">
    <div class="col">

        @if ($cek > 0)

        @foreach ($cekkondisi as $d)

            @if (!empty($d -> jam_in) && empty($d -> jam_out))
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @elseif (!empty($d -> jam_in) && !empty($d -> jam_out))
                <span>Anda Sudah Absen Hari Ini. Terima Kasih</span>
            @endif
        
        @endforeach

        @else
        <!-- <div align="center" style="display:block">
            <h3 align="center">Pilih Waktu Dinas :</h3>
            <form>
                <input type="radio" id="pagi" name="waktuDinas" value="Pagi" onclick="myFunction(this.value)">
                <label for="pagi">PAGI</label>
                <input type="radio" id="siang" name="waktuDinas" value="Siang" onclick="myFunction(this.value)">
                <label for="siang">SIANG</label>
                <input type="radio" id="malam" name="waktuDinas" value="Malam" onclick="myFunction(this.value)">
                <label for="malam">MALAM</label><br>
                <input type="text" id="dinas" hidden>
            </form>
        </div> -->
        <button id="takeabsen" class="btn btn-primary btn-block mt-1">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Masuk
        </button>
        @endif

        <!-- @foreach ($cekkondisi as $d)

            @if ($cek = 0)
                <div align="center" style="display:block">
            <h3 align="center">Pilih Waktu Dinas :</h3>
            <form>
                <input type="radio" id="pagi" name="waktuDinas" value="Pagi" onclick="myFunction(this.value)">
                <label for="pagi">PAGI</label>
                <input type="radio" id="siang" name="waktuDinas" value="Siang" onclick="myFunction(this.value)">
                <label for="siang">SIANG</label>
                <input type="radio" id="malam" name="waktuDinas" value="Malam" onclick="myFunction(this.value)">
                <label for="malam">MALAM</label><br>
                <input type="text" id="dinas" hidden>
            </form>
        </div>
        <button id="takeabsen" class="btn btn-primary btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Masuk
        </button>
            @elseif (!empty($d -> jam_in) && empty($d -> jam_out))
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @elseif (!empty($d -> jam_in) && !empty($d -> jam_out))
                <span>Anda Sudah Absen Hari Ini</span>
            @endif
        
        @endforeach -->
           
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <div id="map" style="height: 400px;"></div>
    </div>
</div>

<audio id="notifikasi_in">
    <source src="{{asset('assets/sound/notifikasi_in.mp3')}}" type="audio/mpeg">
</audio>
<audio id="notifikasi_out">
    <source src="{{asset('assets/sound/notifikasi_out.mp3')}}" type="audio/mpeg">
</audio>
<audio id="notifikasi_outradius">
    <source src="{{asset('assets/sound/notifikasi_outradius.mp3')}}" type="audio/mpeg">
</audio>

@endsection

@push('myscript')

<script type="text/javascript">
    window.onload = function() {
        jam();
    }
 
    function jam() {
        var e = document.getElementById('jam')
            , d = new Date()
            , h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());
 
        e.innerHTML = h + ':' + m + ':' + s;
 
        setTimeout('jam()', 1000);
    }
 
    function set(e) {
        e = e < 10 ? '0' + e : e;
        return e;
    }
 
</script>

<script>
    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    var notifikasi_outradius = document.getElementById('notifikasi_outradius');
    Webcam.set({
        height: 400,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });
    Webcam.attach('.webcam-capture');
    
    var lokasi = document.getElementById('lokasi');
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    // fungsi untuk menyimpan waktu dinas
    //function myFunction(waktuDinas) {
    //    document.getElementById("dinas").value = waktuDinas;
    //}

    // ketika berhasil mendapatkan current position dari user
    function successCallback(position){
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 17);
        var lokasi_kantor = "{{ $lok_kantor -> lokasi_kantor }}";
        var lok = lokasi_kantor.split(",");
        var lat_kantor = lok[0];
        var long_kantor = lok[1];
        var radius_absensi = "{{ $lok_kantor -> radius }}"
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([lat_kantor, long_kantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2,
            radius: radius_absensi
        }).addTo(map);
    }
    // ketika gagal mendapatkan current position dari user
    function errorCallback(){

    }

    // ketika tombol ambil absen di tekan
    $("#takeabsen").click(function(e){
        var lokasi = $("#lokasi").val();
        //var dinas = $("#dinas").val();
        // if(dinas == ''){
        //     //alert('Pilih Waktu Dinas')
        //     Swal.fire({
        //         title: "Info",
        //         text: "Silahkan Pilih Waktu Dinas",
        //         icon: "info"
        //     });
        //}else{
            Webcam.snap(function(uri){
                image = uri;
            });
            // var lokasi = $("#lokasi").val();
            // var dinas = $("#dinas").val();
            $.ajax({
                type:'POST',
                url:'/presensi/store',
                data:{
                    _token:"{{csrf_token()}}",
                    image:image,
                    lokasi:lokasi,
                    //dinas:dinas
                },
                cache:false,
                success:function(respond){
                    var status = respond.split("|");
                    if(status[0] == 'success'){
                        if(status[2] == 'in'){
                            notifikasi_in.play();
                        }else{
                            notifikasi_out.play();
                        }
                        Swal.fire({
                        title: 'Absensi Berhasil',
                        text: status[1],
                        icon: 'success',
                        showConfirmButton: false
                        })
                        setTimeout("location.href='/dashboard'", 3000);
                    } else{
                        if(status[2] == 'radius'){
                            notifikasi_outradius.play();
                        }
                        Swal.fire({
                        title: 'Absensi Gagal',
                        text: status[1],
                        icon: 'error',
                        })
                    }
                }
            });
        }  
    //}
);

</script>
@endpush