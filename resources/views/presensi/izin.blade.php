@extends('layouts.presensi')
@section('header')

<style>
    .historicontent {
        display: flex;
    }
    .datapresensi h3,
    .datapresensi small,
    .datapresensi p {
        margin-left: 10px;
        margin-bottom: 1px; /* Atur jarak sesuai keinginan Anda */
    }
    .statusapproved {
        position: absolute;
        right: 20px;
        text-align: center;
    }
</style>

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pagetitle">Izin dan Sakit</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{$messagesuccess}}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{$messageerror}}
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col">
        <form action="/presensi/izin" method="GET" class="mb-1">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="form-control selectmaterialize">
                            <option value="">Bulan</option>
                            @for ($i = 1; $i <= 12; $i ++ )
                            <option {{ Request('bulan') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $namabulan[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control selectmaterialize">
                            <option value="">Tahun</option>
                            @php
                            $tahun_awal = 2024;
                            $tahun_sekarang = date("Y");
                            for ($t = $tahun_awal; $t <= $tahun_sekarang; $t ++){
                            if(Request('tahun') == $t){
                                $selected = 'selected';
                            }else{
                                $selected = '';
                            }
                            echo"<option $selected value='$t'>$t</option>";
                            }
                            @endphp
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-primary w-100">
                        <ion-icon name="search-outline"></ion-icon> Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col">
        @foreach ($dataizin as $d)
        @php
            if($d->status == "i"){
                $status = "Izin";
            }else if($d->status == "s"){
                $status = "Sakit";
            }
        @endphp
            <div class="card card_izin" kode_izin="{{ $d->kode_izin }}" status_approved="{{ $d->status_approved }}" style="margin-bottom: 5px" data-toggle="modal" data-target="#actionSheetIconed">
                <div class="card-body">
                    <div class="historicontent">
                        <div class="iconpresensi">
                            @if ($d->status == "i")
                                <ion-icon name="airplane-outline" style="font-size: 32px"></ion-icon>
                            @elseif ($d->status == "s")
                                <ion-icon name="medkit-outline" style="font-size: 32px"></ion-icon>
                            @endif
                        </div>
                        <div class="datapresensi">
                            <h3>{{ date("d-m-Y", strtotime($d->tgl_izin_dari)) }} ({{ $status }})</h3>
                            <small>{{ date("d-m-Y", strtotime($d->tgl_izin_dari)) }} - {{ date("d-m-Y", strtotime($d->tgl_izin_sampai)) }}</small>
                            <p>{{ $d->keterangan }}</p>
                            <p style="color: blue">
                                @if (!empty($d->file_surat_izin))
                                    <ion-icon name="attach-outline"></ion-icon> Lihat Surat
                                @endif
                            </p>
                        </div>
                        
                        <div class="statusapproved">
                            @if ($d->status_approved == 0)
                                <span class="badge bg-warning">Pendig</span>
                            @elseif($d->status_approved == 1)
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($d->status_approved == 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                            <p style="margin-top:5px">{{ hitunghari($d->tgl_izin_dari, $d->tgl_izin_sampai) }} hari</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <ul class="listview image-listview">
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <b>{{date("d-m-Y", strtotime($d -> tgl_izin_dari))}} <small>{{$d -> status == "s" ? "Sakit" : "Izin"}}</small></b>
                                <br>
                                <small class="text-muted">{{$d -> keterangan}}</small>
                            </div>
                            @if ($d -> status_approved == 0)
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif ($d -> status_approved == 1)
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($d -> status_approved == 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>
                </li>
            </ul> -->
        @endforeach
    </div>
</div>
<!-- <div class="fab-button bottom-right" style="margin-bottom:70px">
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> -->

<div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">
    <a href="#" class="fab bg-primary" data-toggle="dropdown">
        <ion-icon name="add-outline" class="md hydrated" arial-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">
        <a href="/izinabsen" class="dropdown-item bg-primary">
            <ion-icon name="airplane-outline"></ion-icon>
            <p>Izin</p>
        </a>
        <a href="/izinsakit" class="dropdown-item bg-primary">
            <ion-icon name="medkit-outline"></ion-icon>
            <p>Sakit</p>
        </a>
    </div>
</div>

<div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi</h5>
            </div>
            <div class="modal-body" id="showact">

            </div>
        </div>
    </div>
</div>

<div class="modal fade dialogbox" id="deleteConfirm" data-backdrop="static" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anda Yakin Akan Menghapus Data ?</h5>
            </div>
            <div class="modal-body">
                Data Pengajuan Izin Akan Dihapus
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-dismiss="modal" >Batalkan</a>
                    <a href="" class="btn btn-text-primary" id="hapuspengajuan" >Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function(){
        $(".card_izin").click(function(e){
            var kode_izin = $(this).attr("kode_izin");
            var status_approved = $(this).attr("status_approved");
            // alert(status_approved);
            if (status_approved == 1) {
                Swal.fire({
                    title: 'Info',
                    text: 'Data Sudah Disetujui, Tidak Dapat Di Ubah !',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                });
            }else{
                $("#showact").load('/izin/' + kode_izin + '/showact');
            }
        });
    });
</script>
@endpush