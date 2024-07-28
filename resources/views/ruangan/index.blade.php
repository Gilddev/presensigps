@extends('layouts.admin.tabler')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                  Data Ruangan
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
                                <a href="#" class="btn btn-primary" id="button_tambahruangan">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>    
                                Tambah Data</a>
                            </div>
                        </div>
                        <!-- Bagian body dari tabel -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/ruangan" method="GET">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" placeholder="Ruangan" value="{{ Request('nama_ruangan') }}">
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
                                            <th>Kode Ruangan</th>
                                            <th>Nama Ruangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ruangan as $d)
                                            <tr>
                                                <td>{{ $loop -> iteration }}</td>
                                                <td>{{ $d -> kode_ruangan }}</td>
                                                <td>{{ $d -> nama_ruangan }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="edit" kode_ruangan="{{ $d -> kode_ruangan }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                        <form action="/ruangan/{{ $d -> kode_ruangan }}/delete" method="POST" style="margin-left: 10px">
                                                            @csrf
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
                               
                            </div>
                        </div>

                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<!-- untuk menampilkan modal tambah data -->
    <div class="modal modal-blur fade" id="modal-inputruangan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Data Ruangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/ruangan/store" method="POST" id="formtambahruangan">
                @csrf
                <!-- untuk menampilkan form pengisian data karyawan -->
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                            </span>
                            <input type="text" value="" id="kode_ruangan" class="form-control" placeholder="Kode Ruangan" name="kode_ruangan">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <input type="text" value="" id="nama_ruangan" class="form-control" placeholder="Nama Ruangan" name="nama_ruangan">
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
    <div class="modal modal-blur fade" id="modal-editruangan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Data Ruangan</h5>
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
        $("#button_tambahruangan").click(function(){
            $("#modal-inputruangan").modal("show");
        });

        $(".edit").click(function() {
            var kode_ruangan = $(this).attr('kode_ruangan');
            $.ajax({
                type: 'POST',
                url: '/ruangan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_ruangan: kode_ruangan
                },
                success: function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editruangan").modal("show");
        });

        $(".delete-confirm").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            //alert('test');
            Swal.fire({
                title: "Hapus data ruangan ?",
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

        $("#formtambahruangan").submit(function(){
        var kode_ruangan = $("#kode_ruangan").val();
        var nama_ruangan = $("#nama_ruangan").val();
            if (kode_ruangan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Kode ruangan masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#kode_ruangan").focus();
                return false;
            } else if(nama_ruangan == ""){
                Swal.fire({
                    title: 'Info',
                    text: 'Nama ruangan masih kosong',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
                $("#nama_ruangan").focus();
                return false;
            }
        });
    });
</script>
@endpush