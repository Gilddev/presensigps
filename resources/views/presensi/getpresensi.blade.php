@php
function selisih($jam_masuk, $jam_keluar)
        {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
@endphp

@foreach ($presensi as $d)
@php
    $pathFotoIn = Storage::url('upload/absensi/' . $d -> foto_in);
    $pathFotoOut = Storage::url('upload/absensi/' . $d -> foto_out);
@endphp
    <tr>
        <td>{{ $loop -> iteration }}</td>
        <td>{{ $d -> nik }}</td>
        <td>{{ $d -> nama_lengkap }}</td>
        <td>{{ $d -> nama_ruangan }}</td>
        <td>{{ $d -> dinas }}</td>
        <td>{{ $d -> jam_in }}</td>
        <td>
            <img src="{{ url($pathFotoIn) }}" class="avatar" alt=""></td>
        <td>{!! $d -> jam_out != null ? $d -> jam_out : '<span class="badge bg-warning" style="color: white">Belum Absen</span>' !!}</td>
        <td>
            @if ($d -> jam_out != null)
            <img src="{{ url($pathFotoOut) }}" class="avatar" alt="">
            @else
            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="avatar" alt="">
            @endif
        </td>
        <td>
            @if ($d -> dinas == 'Pagi' && $d -> jam_in > '08:00')
            @php
                $jam_terlambat = selisih('08:00:00', $d -> jam_in);
            @endphp
            <span class="badge bg-danger" style="color:white">Terlambat {{ $jam_terlambat }}</span>

            @elseif ($d -> dinas == 'Siang' && $d -> jam_in > '13:00')
            @php
                $jam_terlambat = selisih('13:00:00', $d -> jam_in);
            @endphp
            <span class="badge bg-danger" style="color:white">Terlambat {{ $jam_terlambat }}</span>
            
            @elseif ($d -> dinas == 'Malam' && $d -> jam_in > '20:00')
            @php
                $jam_terlambat = selisih('21:00:00', $d -> jam_in);
            @endphp
            <span class="badge bg-danger" style="color:white">Terlambat {{ $jam_terlambat }}</span>

            @else
            <span class="badge bg-success" style="color:white">Tepat Waktu</span>
            @endif
        </td>
        <td>
            <a href="#" class="btn btn-primary tampilkanpeta" id="{{ $d -> id }}">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-text"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
            </a>
        </td>
    </tr>
@endforeach

<script>
    $(function(){
        $(".tampilkanpeta").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '/tampilkanpeta',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond){
                    $("#loadmap").html(respond);
                }
            });
            $("#modal-tampilkanpeta").modal("show");
        });
    });
</script>