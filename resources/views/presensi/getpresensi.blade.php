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
        <td>{!! $p->check_out_time != null ? $p->check_out_time : '<span class="badge bg-danger">Belum Absen</span>' !!}</td>
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
                <span class="badge bg-danger text-white">Terlambat</span>
            @else
                <span class="badge bg-success text-white">Tepat Waktu</span>
            @endif
        </td>
    </tr>
@endforeach
