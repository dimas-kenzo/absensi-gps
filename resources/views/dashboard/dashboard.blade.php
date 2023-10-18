@extends('layouts.presensi')
@section('header')
<style>
    .notif-rekap {
        position: absolute;
        top: 3px;
        right: 10px;
        font-size: 0.5rem;
        z-index: 999;
    }
</style>
@endsection
@section('content')
<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            @if (!empty(Auth::guard('web')->user()->photo))
                @php
                    $path = Storage::url('uploads/users/'.Auth::guard('web')->user()->photo)
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ Auth::guard('web')->user()->name }}</h2>
            <span id="user-role">{{ Auth::guard('web')->user()->position }}</span>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('presensi.edit') }}" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('presensi.izin') }}" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('presensi.history') }}" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="orange" style="font-size: 40px;">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Lokasi
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <ion-icon name="log-out" class="orange" style="font-size: 40px;"></ion-icon>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    <div class="menu-name">
                        Keluar
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence mx-2">
                                @if ($presensiHariIni)
                                    @php
                                        $path = Storage::url('uploads/absensi/'.$presensiHariIni->photo_in)
                                    @endphp
                                    <img src="{{ url($path) }}" alt="Foto Masuk" class="imaged w64">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensiHariIni != null ? $presensiHariIni->check_in_time : 'Belum Absen'  }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence mx-2">
                                @if ($presensiHariIni != null && $presensiHariIni->check_out_time != null)
                                    @php
                                        $path = Storage::url('uploads/absensi/'.$presensiHariIni->photo_out)
                                    @endphp
                                    <img src="{{ url($path) }}" alt="Foto Masuk" class="imaged w64">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensiHariIni != null && $presensiHariIni->check_out_time != null ? $presensiHariIni->check_out_time : 'Belum Absen'  }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="rekappresence">
        <div id="chartdiv"></div>
        <!-- <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence primary">
                                <ion-icon name="log-in"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Hadir</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence green">
                                <ion-icon name="document-text"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Izin</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence warning">
                                <ion-icon name="sad"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Sakit</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence danger">
                                <ion-icon name="alarm"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Terlambat</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div> --}}

    <div class="rekapPresensi">
        <h3>Rekap Presensi {{ $namaBulan[$bulanIni] }} {{ $tahunIni }} </h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important">
                        <span class="badge bg-danger notif-rekap">{{ $rekapPresensi->jmlHadir }}</span>
                        <ion-icon name="happy-outline" style="font-size: 1.5rem" class="text-primary"></ion-icon><br>
                        <span style="font-size: 0.8rem">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important">
                        <span class="badge bg-danger notif-rekap">{{ $rekapIzin->jmlIzin }}</span>
                        <ion-icon name="reader-outline" style="font-size: 1.5rem" class="text-success"></ion-icon><br>
                        <span style="font-size: 0.8rem">Ijin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important">
                        <span class="badge bg-danger notif-rekap">{{ $rekapIzin->jmlSakit }}</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.5rem" class="text-danger"></ion-icon><br>
                        <span style="font-size: 0.8rem">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important">
                        <span class="badge bg-danger notif-rekap">{{ $rekapPresensi->jmlTelat }}</span>
                        <ion-icon name="alarm-outline" style="font-size: 1.5rem" class="text-warning"></ion-icon><br>
                        <span style="font-size: 0.8rem">Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($historiBulanIni as $h)
                    @php
                        $path = Storage::url('uploads/absensi/'.$h->photo_in)
                    @endphp
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                {{-- <img src="{{ url($path) }}" alt="foto_in" class="imaged w64"> --}}
                                <ion-icon name="checkbox-outline" role="img" class="md hydrated"
                                    aria-label="image outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>{{ date("d-m-Y",strtotime($h->attendance_date)) }}</div>
                                <div class="mt-2">
                                    <span class="badge badge-success mb-2">{{ $h->check_in_time }}</span>
                                    <span class="badge badge-danger">{{ $presensiHariIni != null && $presensiHariIni->check_out_time != null ? $h->check_out_time : 'Belum Absen' }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboard as $l)
                    <li>
                        <div class="item">
                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    <b>{{ $l->name }}</b> <br>
                                    <small class="text-muted">{{ $l->position }}</small>
                                </div>
                                <span class="badge {{ $l->check_in_time < "07.00" ? "bg-success" : "bg-danger" }}">{{ $l->check_in_time }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection