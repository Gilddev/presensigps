@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

<style>
    .datepicker-modal {
        max-height: 430px !important;
    }
    .datepicker-date-display {
        background-color: #0f3a7e !important;
    }
    #keterangan {
        height: 8rem !important;
    }
    .element {
        max-width: 100%; /* Gambar tidak akan lebih besar dari kontainer induk */
        height: auto; /* Tinggi otomatis menyesuaikan dengan lebar, menjaga rasio */
    }
</style>

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pagetitle">Form Edit Izin Absen</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px; margin-bottom: 70px">
    <div class="col">
        <form method="POST" action="/izinabsen/{{ $dataizin->kode_izin }}/update" id="formizin" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <input type="text" id="tgl_izin_dari" value="{{ $dataizin->tgl_izin_dari }}" name="tgl_izin_dari" class="form-control datepicker" placeholder="Dari" autocomplete="off">
            </div>

            <div class="form-group">
                <input type="text" id="tgl_izin_sampai" value="{{ $dataizin->tgl_izin_sampai }}" name="tgl_izin_sampai" class="form-control datepicker" placeholder="Sampai" autocomplete="off">
            </div>
            
            <div class="form-group">
                <input type="text" id="jml_hari" name="jml_hari" placeholder="Jumlah Hari" autocomplete="off" disabled>
            </div>
            <p>Surat Keterangan Izin</p>
            @if ($dataizin->file_surat_izin != null)
                <div class="row">
                    <div class="col-12">
                        @php
                            $filesuratizin = Storage::url('upload/sid/' . $dataizin->file_surat_izin);
                        @endphp
                        <img src="{{ url($filesuratizin) }}" class="element" alt="">
                    </div>
                </div>
            @endif
            <p>Ganti Surat Keterangan Izin</p>
            <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                <input type="file" name="sid" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            <i>Tekan untuk Upload Gambar</i>
                        </strong>
                    </span>
                </label>
            </div>

            <div class="form-group">
                <input type="text" id="keterangan" value="{{ $dataizin->keterangan }}" name="keterangan" placeholder="Keterangan" autocomplete="off" >
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

        function loadjumlahhari(){
            var dari = $("#tgl_izin_dari").val();
            var sampai = $("#tgl_izin_sampai").val();
            var date1 = new Date(dari); 
            var date2 = new Date(sampai); 

            var Difference_In_Time = date2.getTime() - date1.getTime();

            var Difference_In_Days =Difference_In_Time / (1000 * 3600 * 24);
            
            if(dari == "" || sampai == ""){
                var jml_hari_text = 0;
            }else{
                var jml_hari_text = Difference_In_Days + 1;
            }

            $("#jml_hari").val(jml_hari_text + " Hari");
        }

        loadjumlahhari();

        $("#tgl_izin_dari, #tgl_izin_sampai").change(function(e){
            loadjumlahhari();
        });

        // $("#tgl_izin").change(function(e){
        //     var tgl_izin = $(this).val();
        //     $.ajax({
        //         type: 'POST',
        //         url: '/presensi/cekpengajuanizin',
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             tgl_izin: tgl_izin
        //         },
        //         cache: false,
        //         success: function(respond){
        //             if(respond == 1){
        //                 swal.fire({
        //                     title: 'Oops !',
        //                     text: 'Anda Sudah Mengajukan Pada Tanggal Tersebut',
        //                     icon: 'warning'
        //                 }).then((result) => {
        //                     $("#tgl_izin").val("");
        //                 });
        //             }
        //         }
        //     });
        // });

        $("#formizin").submit(function(){
            var tgl_izin_dari = $("#tgl_izin_dari").val();
            var tgl_izin_sampai = $("#tgl_izin_sampai").val();
            var keterangan = $("#keterangan").val();
            if(tgl_izin_dari == "" || tgl_izin_sampai == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Anda belum memilih tanggal',
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