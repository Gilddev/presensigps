@extends('layouts.admin.tabler')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                  Data Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- Bagian tombol tambah -->
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btn_tambahkaryawan">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>    
                                Tambah Data</a>
                            </div>
                        </div>
                        <!-- Bagian body dari tabel -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/karyawan" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="kode_ruangan" id="kode_ruangan" class="form-select">
                                                    <option value="Ruangan">Ruangan</option>    
                                                    @foreach ($ruangan as $d)
                                                        <option {{ Request('kode_ruangan') == $d -> kode_ruangan ? 'selected' : ''}} value="{{ $d -> kode_ruangan }}">{{ $d -> nama_ruangan }}</option>
                                                    @endforeach      
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div> 
                                </form>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-border">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nik</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Ruangan</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Alamat</th>
                                            <th>No HP</th>
                                            <th>Foto</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($karyawan as $d)
                                        @php
                                            $path = Storage::url('upload/karyawan/'. $d -> foto);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop -> iteration + $karyawan -> firstItem() - 1 }}</td>
                                            <td>{{ $d -> nik }}</td>
                                            <td>{{ $d -> nama_lengkap }}</td>
                                            <td>{{ $d -> jabatan }}</td>
                                            <td>{{ $d -> nama_ruangan }}</td>
                                            <td>{{ $d -> jenis_kelamin }}</td>
                                            <td>{{ $d -> alamat }}</td>
                                            <td>{{ $d -> no_hp }}</t>
                                            <td>
                                                @if (empty($d -> foto))
                                                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="avatar" alt="">
                                                @else
                                                    <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                <!-- <a href="#" class="edit btn btn-info btn-sm" nik="{{ $d -> nik }}"> -->

                                                <a href="#" class="edit" nik="{{ $d -> nik }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                </a>

                                                <a href="/konfigurasi/{{ $d->nik }}/setjamkerja" class="" style="margin-left: 5px">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                                </a>

                                                <form action="/karyawan/{{ $d -> nik }}/delete" method="POST" style="margin-left: 5px">
                                                    @csrf
                                                    <!-- <a class="btn btn-danger btn-sm delete-confirm"> -->
                                                    <a href="#" class="delete-confirm">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    </a>
                                                </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $karyawan -> links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>

                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<!-- untuk menampilkan modal tambah data -->
    <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/karyawan/store" method="POST" id="formtambahkaryawan" enctype="multipart/form-data">
                @csrf
                <!-- untuk menampilkan form pengisian data karyawan -->
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                            </span>
                            <input type="text" value="" id="nik" class="form-control" placeholder="Nik" name="nik" maxlength="16">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <input type="text" value="" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-align-justified"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l12 0" /></svg>
                            </span>
                            <input type="text" value="" id="jabatan" class="form-control" placeholder="Jabatan" name="jabatan">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <select name="kode_ruangan" id="kode_ruangan" class="form-select">
                            <option value="">Ruangan</option>    
                            @foreach ($ruangan as $d)
                                <option {{ Request('kode_ruangan') == $d -> kode_ruangan ? 'selected' : ''}} value="{{ $d -> kode_ruangan }}">{{ $d -> nama_ruangan }}</option>
                            @endforeach      
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="form-label">Jenis Kelamin</div>
                            <div>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" id="jenis_kelamin" value="Laki-laki" type="radio" name="jenis_kelamin" checked="">
                                    <span class="form-check-label">Laki-laki</span>
                                </label>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" id="jenis_kelamin" value="Perempuan" type="radio" name="jenis_kelamin">
                                    <span class="form-check-label">Perempuan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                            </span>
                            <input type="text" value="" id="alamat" class="form-control" placeholder="Alamat" name="alamat">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                            </span>
                            <input type="text" value="" id="no_hp" class="form-control" placeholder="No HP" name="no_hp">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                    <div class="mb-3">
                            <div class="form-label">Upload Foto</div>
                            <input type="file" name="foto" class="form-control">
                          </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>    
                            Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- untuk menampilkan modal edit data -->
    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="loadeditform">
            
          </div>
        </div>
      </div>
    </div>
@endsection

@push('myscript')
<script>
    $(function(){

        $("#nik").mask("0000000000000000");
        $("#btn_tambahkaryawan").click(function(){
            $("#modal-inputkaryawan").modal("show");
        });

        $(".edit").click(function() {
            var nik = $(this).attr('nik');
            //alert(nik);
            $.ajax({
                type: 'POST',
                url: '/karyawan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik
                },
                success: function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editkaryawan").modal("show");
        });

        $(".delete-confirm").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            //alert('haha');
            Swal.fire({
                title: "Hapus data karyawan ?",
                text: "Data akan terhapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus data"
                }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                    title: "Terhapus!",
                    text: "Data berhasil dihapus.",
                    icon: "success"
                    });
                }
            });
        });
        
        $("#formtambahkaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var jabatan = $("#jabatan").val();
            var kode_ruangan = $("formtambahkaryawan").find("#kode_ruangan").val();
            var jenis_kelamin = $("#jenis_kelamin").val();
            var alamat = $("#alamat").val();
            var no_hp = $("#no_hp").val();
            if (nik == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Nik masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#nik").focus();
                return false;
            } else if(nama_lengkap == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Nama masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#nama_lengkap").focus();
                return false;
            } else if(jabatan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Jabatan masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#jabatan").focus();
                return false;
            } else if(kode_ruangan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Silahkan pilih Ruangan',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#kode_ruangan").focus();
                return false;
            } else if(alamat == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'alamat masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#alamat").focus();
                return false;
            } else if(jabatan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Jabatan masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#jabatan").focus();
                return false;
            } else if(no_hp == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'No HP masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#no_hp").focus();
                return false;
            }
            //$("#nik").focus();
            //#return false;
        });
    });
</script>
@endpush