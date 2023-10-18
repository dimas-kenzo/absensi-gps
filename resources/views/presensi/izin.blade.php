@extends('layouts.presensi')
@section('header')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Animasi fade-out */
        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Atur animasi untuk pesan sukses dan pesan error */
        .alert {
            animation-duration: 3s;
            /* Durasi animasi 3 detik */
            animation-timing-function: ease-in-out;
            /* Gaya pergerakan animasi */
        }

        .alert-success {
            animation-name: fadeIn;
            /* Menerapkan animasi fade-in */
        }

        .alert-warning {
            animation-name: fadeOut;
            /* Menerapkan animasi fade-out */
        }
    </style>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Cuti</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            @php
                $message_success = Session::get('success');
                $message_error = Session::get('error');
            @endphp
            @if ($message_success)
                <div class="alert alert-success">
                    {{ $message_success }}
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.alert-success').style.display = 'none';
                    }, 3000); // Menghilangkan pesan sukses dalam 3 detik
                </script>
            @endif
            @if ($message_error)
                <div class="alert alert-warning">
                    {{ $message_error }}
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.alert-warning').style.display = 'none';
                    }, 3000); // Menghilangkan pesan error dalam 3 detik
                </script>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            @foreach ($dataizin as $d)
                <ul class="listview image-listview">
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <b>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}( {{ $d->status == 'S' ? 'Sakit' : 'Izin' }} )</b><br> 
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                </div>
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Waiting</span>
                                @elseif ($d->status_approved == 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($d->status_approved == 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
    <div class="fab-button bottom-right" style="margin-bottom: 70px">
        <a href="{{ route('presensi.buatizin') }}" class="fab">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
@endsection
