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
@foreach ($presensi as $p)
    @php
        $masuk = Storage::url('public/uploads/absensi/' . $p->photo_in);
        $keluar = Storage::url('public/uploads/absensi/' . $p->photo_out);
        $path = Storage::url('public/uploads/no-image/no-image.png');
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $p->nik }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->position }}</td>
        <td>{{ $p->check_in_time }}</td>
        <td>
            @if ($p->photo_in)
                <img src="{{ url($masuk) }}" class="avatar" alt="">
            @else
                <img src="{{ url($path) }}" alt="" class="avatar">
            @endif
        </td>
        <td>{!! $p->check_out_time != null ? $p->check_out_time : '<span class="badge bg-danger text-white">Belum Absen</span>' !!}</td>
        <td>
            @if ($p->check_out_time != null)
                <img src="{{ url($keluar) }}" class="avatar" alt="">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-hexagon" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z">
                    </path>
                    <path d="M12 8v4"></path>
                    <path d="M12 16h.01"></path>
                </svg>
            @endif
        </td>
        <td>
            @if ($p->check_in_time >= '07:00')
                @php
                    $jam_terlambat = selisih('07:00:00', $p->check_in_time)
                @endphp
                <span class="badge bg-danger text-white">Terlambat {{ $jam_terlambat }}</span>
            @else
                <span class="badge bg-success text-white">Tepat Waktu</span>
            @endif
        </td>
        <td>
            <a href="#" class="d-flex justify-content-center tampilPeta" id="{{ $p->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-location-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M20.891 2.006l.106 -.006l.13 .008l.09 .016l.123 .035l.107 .046l.1 .057l.09 .067l.082 .075l.052 .059l.082 .116l.052 .096c.047 .1 .077 .206 .09 .316l.005 .106c0 .075 -.008 .149 -.024 .22l-.035 .123l-6.532 18.077a1.55 1.55 0 0 1 -1.409 .903a1.547 1.547 0 0 1 -1.329 -.747l-.065 -.127l-3.352 -6.702l-6.67 -3.336a1.55 1.55 0 0 1 -.898 -1.259l-.006 -.149c0 -.56 .301 -1.072 .841 -1.37l.14 -.07l18.017 -6.506l.106 -.03l.108 -.018z" stroke-width="0" fill="currentColor"></path>
                 </svg>
            </a>
        </td>
    </tr>
@endforeach
<script>
    $(function(){
        $(".tampilPeta").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '/tampil',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                cache: false,
                success: function(respond){
                    $("#loadMap").html(respond);
                }
            })
            $("#modalShowMap").modal("show");
        })
    })
</script>
