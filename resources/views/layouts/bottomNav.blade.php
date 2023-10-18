{{-- <div class="appBottomMenu">
    <a href="{{ route('dashboard') }}" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('presensi.history') }}" class="item {{ request()->is('presensi/history*') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>
    <a href="{{ route('presensi.create') }}" class="item {{ request()->is('presensi/create') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('presensi.izin') }}" class="item {{ request()->is('presensi/izin*') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>
    <a href="{{ route('presensi.edit') }}" class="item {{ request()->is('presensi/edit*') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div> --}}
{{-- Atau --}}
<div class="appBottomMenu">
    <a href="{{ route('dashboard') }}" class="item {{ Request::is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('presensi.history') }}" class="item {{ Request::is('presensi/history') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>
    <a href="{{ route('presensi.create') }}" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('presensi.izin') }}" class="item {{ Request::is('presensi/izin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>
    <a href="{{ route('presensi.edit') }}" class="item {{ Request::is('presensi/edit') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
