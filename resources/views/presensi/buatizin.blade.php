@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pagetitle">Form Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="formizin">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal" autocomplete="off">
            </div>
            <div class="for-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin/Sakit</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group" style="margin-top: 10px">
                <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" placeholder="Keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form> 
    </div>
</div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"    
        });

        $("#tgl_izin").change(function(e){
            var tgl_izin = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/presensi/cekpengajuanizin',
                data: {
                    _token: "{{ csrf_token() }}",
                    tgl_izin: tgl_izin
                },
                cache: false,
                success: function(respond){
                    if(respond == 1){
                        swal.fire({
                            title: 'Oops !',
                            text: 'Anda Sudah Mengajukan Pada Tanggal Tersebut',
                            icon: 'warning'
                        }).then((result) => {
                            $("#tgl_izin").val("");
                        });
                    }
                }
            });
        });

        $("#formizin").submit(function(){
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
            if(tgl_izin == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Anda belum memilih tanggal',
                    icon: 'warning'
                });
                return false;
            } else if(status == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Anda belum memilih status',
                    icon: 'warning'
                });
                return false;
            } else if(keterangan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Anda belum mengisi keterangan',
                    icon: 'warning'
                });
                return false;
            }
        });
    });
</script>
@endpush