<form action="/konfigurasi/updatejamkerja" method="POST" id="formeditjamkerja">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-scan"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 12l14 0" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->kode_jam_kerja }}" id="kode_jam_kerja_edit" class="form-control" placeholder="Kode Jam Kerja" name="kode_jam_kerja">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->nama_jam_kerja }}" id="nama_jam_kerja_edit" class="form-control" placeholder="Nama Jam Kerja" name="nama_jam_kerja">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->awal_jam_masuk }}" id="awal_jam_masuk_edit" class="form-control" placeholder="Awal Jam Masuk" name="awal_jam_masuk">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->jam_masuk }}" id="jam_masuk_edit" class="form-control" placeholder="Jam Masuk" name="jam_masuk">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->akhir_jam_masuk }}" id="akhir_jam_masuk_edit" class="form-control" placeholder="Akhir Jam Masuk" name="akhir_jam_masuk">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
                                </span>
                                <input type="text" value="{{ $jam_kerja->jam_pulang }}" id="jam_pulang_edit" class="form-control" placeholder="Jam Pulang" name="jam_pulang">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="lintashari" id="lintashari_edit" class="form-select">
                                    <option value="">Lintas Hari</option>
                                    <option value="1" {{ $jam_kerja->lintashari == 1 ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $jam_kerja->lintashari == 0 ? 'selected' : '' }}>Tidak</option>
                                </select>
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
<script>
    $("#formeditjamkerja").submit(function() {
        var kode_jam_kerja = $("#kode_jam_kerja_edit").val();
        var nama_jam_kerja = $("#nama_jam_kerja_edit").val();
        var awal_jam_masuk = $("#awal_jam_masuk_edit").val();
        var jam_masuk = $("#jam_masuk_edit").val();
        var akhir_jam_masuk = $("#akhir_jam_masuk_edit").val();
        var jam_pulang = $("#jam_pulang_edit").val();
        var lintashari = $("#lintashari_edit").val();

                if(kode_jam_kerja == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Kode Jam Kerja Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#kode_jam_kerja").focus();
                    });     
                    return false;               
                }else if(nama_jam_kerja == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Nama Jam Kerja Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#nama_jam_kerja").focus();
                    });     
                    return false; 
                }else if(awal_jam_masuk == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Awal Jam Masuk Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#awal_jam_masuk").focus();
                    });     
                    return false; 
                }else if(jam_masuk == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Jam Masuk Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#jam_masuk").focus();
                    });     
                    return false; 
                }else if(akhir_jam_masuk == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Akhir Jam Masuk Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#akhir_jam_masuk").focus();
                    });     
                    return false; 
                }else if(jam_pulang == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Jam Pulang Masih Kosong!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#jam_pulang").focus();
                    });     
                    return false; 
                }else if(lintashari == ""){
                    Swal.fire({
                        title: "Warning",
                        text: "Pilih Lintas Hari!",
                        icon: "warning",
                        confirmButtonText: "Oke"
                    }).then((result) => {
                        $("#lintashari").focus();
                    });     
                    return false; 
                }
            });
</script>