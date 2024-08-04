@extends('layouts.presensi')
@section('content')

<style>
    .logout{
        position: absolute;
        color: white;
        font-size: 30px;
        text-decoration: none;
        right: 8px;
    }
    .logout:hover{
        color: white;
    }
</style>

<div class="section" id="user-section">
    <a href="/proseslogout" class="logout">
        <ion-icon name="log-out-outline"></ion-icon>
    </a>
            <div id="user-detail">
                <div class="avatar">
                    @if (!empty(Auth::guard('karyawan') -> user() -> foto))
                        @php
                            $path = Storage::url('upload/karyawan/'.Auth::guard('karyawan') -> user() -> foto);
                        @endphp
                        <div class="image-container">
                            <img src="{{url($path)}}" alt="avatar" class="center-cropped-img">
                        </div>
                            
                        <!-- <img src="{{url($path)}}" alt="avatar" class="imaged w64 rounded"> -->
                    @else
                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{Auth::guard('karyawan') -> user() -> nama_lengkap}}</h2>
                    <span id="user-role">{{Auth::guard('karyawan') -> user() -> jabatan}}</span>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/editprofile" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <!-- <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div> -->
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">

                    <div class="col-6">
                        @php
                            if ($presensihariini && $presensihariini->foto_in) {
                                $path = Storage::url('upload/absensi/' . $presensihariini->foto_in);
                            } else {
                                // URL gambar default jika gambar tidak ada di database
                                $path = 'assets/img/sample/avatar/default.jpg';
                            }
                        @endphp
                        <script>
                            function showinimages(event) {
                                Swal.fire({
                                    text: "Absen Masuk",
                                    imageUrl: "{{ url($path) }}", // Pastikan URL dimasukkan sebagai string yang valid
                                    imageWidth: 400,
                                    imageHeight: 300,
                                    imageAlt: "Custom image"
                                });
                            }
                        </script>
                        <a href="#" onclick="showinimages(event)">
                            <div class="card gradasigreen">
                                <div class="card-body">
                                    <div class="presencecontent">
                                        <div class="iconpresence">
                                            @if ($presensihariini != null)
                                                @php
                                                    $path = Storage::url('upload/absensi/' . $presensihariini->foto_in);
                                                @endphp
                                                <img src="{{ url($path) }}" alt="" class="imaged w64">
                                            @else
                                                <ion-icon name="camera"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle">Masuk</h4>
                                            <span>{{ $presensihariini ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                                            {{--<span>{{$presensihariini != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6">
                        @php  
                            if ($presensihariini && $presensihariini->foto_out) {
                                $path = Storage::url('upload/absensi/' . $presensihariini->foto_out);
                            } else {
                                // URL gambar default jika gambar tidak ada di database
                                $path = 'assets/img/sample/avatar/default.jpg';
                            }
                        @endphp
                        <script>
                            function showoutimages(event) {
                                Swal.fire({
                                    text: "Absen Pulang",
                                    imageUrl: "{{ url($path) }}", // Pastikan URL dimasukkan sebagai string yang valid
                                    imageWidth: 400,
                                    imageHeight: 300,
                                    imageAlt: "Custom image"
                                });
                            }
                        </script>
                        <a href="#" onclick="showoutimages(event)">
                            <div class="card gradasired">
                                <div class="card-body">
                                    <div class="presencecontent">
                                        <div class="iconpresence">
                                        @if ($presensihariini != null && $presensihariini -> jam_out != null)
                                                @php
                                                    $path = Storage::url('upload/absensi/' . $presensihariini -> foto_out);
                                                @endphp
                                                <img src="{{url($path)}}" alt="" class="imaged w64">
                                            @else
                                                <ion-icon name="camera"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle">Pulang</h4>
                                            <span>{{$presensihariini && $presensihariini->jam_out != null ? $presensihariini-> jam_out : 'Belum Absen'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </a>
                    </div>

                </div>
            </div>

            <div id="rekappresensi">
                <h3>Rekap Presensi Bulan {{$namabulan[(int)$bulanini]}} {{$tahunini}}</h3>
                <div class="row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important">
                                <span class="badge bg-danger" style="position: absolute; top: 3px; right: 8px; font-size: 0.6rem; z-index:999">
                                    {{$rekappresensi->jmlhadir}}
                                </span>
                                <ion-icon name="accessibility-outline" style="font-size: 1.6rem" class="text-primary mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem font-weight: 500">Hadir</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important">
                                <span class="badge bg-danger" style="position: absolute; top: 3px; right: 8px; font-size: 0.6rem; z-index:999">
                                    {{$rekapizin -> jmlizin}}
                                </span>
                                <ion-icon name="document-outline" style="font-size: 1.6rem" class="text-success mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem font-weight: 500">Izin</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important">
                                <span class="badge bg-danger" style="position: absolute; top: 3px; right: 8px; font-size: 0.6rem; z-index:999">
                                    {{$rekapizin -> jmlsakit}}    
                                </span>
                                <ion-icon name="medkit-outline" style="font-size: 1.6rem" class="text-warning mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem font-weight: 500">Sakit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important">
                                <span class="badge bg-danger" style="position: absolute; top: 3px; right: 8px; font-size: 0.6rem; z-index:999">
                                    {{$rekappresensi->jmlterlambat}}
                                </span>
                                <ion-icon name="timer-outline" style="font-size: 1.6rem" class="text-danger mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem font-weight: 500">Telat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <!-- <ul class="listview image-listview">
                            @foreach ($historibulanini as $d)
                            @php
                                $path =  Storage::url('upload/absensi/' . $d -> foto_in);
                            @endphp
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="clipboard-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>{{date("d-m-Y", strtotime($d -> tgl_presensi))}}</div>
                                        
                                        <span class="badge badge-success">{{ $d -> jam_in}}</span>
                                        <span class="badge badge-danger">{{$d->jam_out != null ? $d-> jam_out : 'Belum Absen'}}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul> -->

                        <style>
                            .historicontent{
                                display: flex;
                            }
                            .datapresensi h3,
                            .datapresensi p{
                                margin-left: 10px;
                                margin-bottom: 1px; /* Atur jarak sesuai keinginan Anda */
                            }
                        </style>

                        @foreach ($historibulanini as $d)
                        @if ($d->status == "h")
                            <div class="card" style="margin-bottom: 5px">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="iconpresensi">
                                            <ion-icon name="finger-print-outline" style="font-size: 48px;" class="text-success"></ion-icon>
                                        </div>
                                        <div class="datapresensi">
                                            <h3>{{ $d->nama_jam_kerja }}</h3>
                                            <p>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</p>
                                            <p>
                                                {!! $d->jam_in != null ? date("H:i", strtotime($d->jam_in)) : '<span class="text-danger">Belum Absen Masuk</span>' !!} - 
                                                {!! $d->jam_out != null ? date("H:i", strtotime($d->jam_out)) : '<span class="text-danger">Belum Absen Pulang</span>' !!} 
                                            </p>
                                            <div id="keterangan">
                                                @php
                                                    // jam ketika karyawan absen
                                                    $jam_in = date("H:i", strtotime($d->jam_in));

                                                    // jam jadwal masuk 
                                                    $jam_masuk = date("H:i", strtotime($d->jam_masuk));

                                                    $jadwal_jam_masuk = $d->tgl_presensi . " " . $jam_masuk;
                                                    $jam_presesi = $d->tgl_presensi . " " . $jam_in;
                                                @endphp

                                                @if ($jam_in > $jam_masuk)
                                                @php
                                                    $jml_jam_terlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presesi)
                                                @endphp
                                                    <p class="danger">Terlambat {{ $jml_jam_terlambat }}</p>
                                                @else
                                                    <p style="color:green">Tepat Waktu</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($d->status == "i")
                            <div class="card" style="margin-bottom: 5px">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="iconpresensi">
                                            <ion-icon name="airplane-outline" style="font-size: 48px;" class="text-success"></ion-icon>
                                        </div>
                                        <div class="datapresensi">
                                            <h3>IZIN</h3>
                                            <p>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }} ({{$d->kode_izin}})</p>
                                            <p>{{ $d->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        @elseif ($d->status == "s")
                            <div class="card" style="margin-bottom: 5px">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="iconpresensi">
                                            <ion-icon name="medkit-outline" style="font-size: 48px;" class="text-success"></ion-icon>
                                        </div>
                                        <div class="datapresensi">
                                            <h3>SAKIT</h3>
                                            <p>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }} ({{$d->kode_izin}})</p>
                                            <p>{{ $d->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        @endif
                        @endforeach

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($leaderboard as $d)
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                            <b>{{$d -> nama_lengkap}}</b>
                                            <br>
                                            <small class="text-muted">{{$d -> jabatan}}</small>
                                        </div>
                                        <span class="badge {{$d -> jam_in < "07:00" ? "bg-success" : "bg-danger"}}">
                                            {{$d -> jam_in}}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
@endsection