@if ($history->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@endif

@foreach ($history as $h)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/absensi/'.$h->photo_in)
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date("d-m-Y",strtotime($h->attendance_date)) }}</b> 
                    </div>
                    <span
                        class="badge {{ $h->check_in_time < '07.00' ? 'bg-success' : 'bg-danger' }}">{{ $h->check_in_time }}</span>
                    <span class="badge badge-primary">{{ $h->check_out_time }}</span>
                </div>
        </li>
    </ul>
@endforeach
