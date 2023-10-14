@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }

        .leaflet-control-attribution {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <input type="text" name="" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($check > 0)
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>Absen Pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>Absen Masuk
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
    <audio id="notif_in">
        <source src="{{ asset('assets/sound/in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_out">
        <source src="{{ asset('assets/sound/out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="radius_sound">
        <source src="{{ asset('assets/sound/rad.mp3') }}" type="audio/mpeg">
    </audio>
@endsection
@push('script')
    <script>
        var notif_in = document.getElementById('notif_in');
        var notif_out = document.getElementById('notif_out');
        var radius_sound = document.getElementById('radius_sound');
        Webcam.set({
            height: '480',
            width: '640',
            image_format: 'jpg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');
        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            // var circle = L.circle([-7.626136866059694, 109.58462431534495], { -7.6706688716518, 109.66083880761339
            var circle = L.circle([-7.6706688716518, 109.66083880761339], { 
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 65
            }).addTo(map);
        }

        function errorCallback(position) {

        }

        $('#takeabsen').click(function(e) {
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $('#lokasi').val();
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: '{{ csrf_token() }}',
                    image: image,
                    lokasi: lokasi,
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split('|');
                    if (status[0] == 'success') {
                        if (status[2] == 'in') {
                            notif_in.play();
                        } else {
                            notif_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil Absen',
                            text: status[1],
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })
                        setTimeout(function() {
                            location.href = '{{ route('dashboard') }}';
                        }, 3000);

                    } else {
                        if (status[2] == 'radius') {
                            radius_sound.play();
                        }
                        Swal.fire({
                            title: 'Gagal Absen',
                            text: status[1],
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })
        })
    </script>
@endpush
