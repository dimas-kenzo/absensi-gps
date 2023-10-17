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
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 4rem">
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
    <form action="{{ route('presensi.update', ['id' => $users->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $users->name }}" name="name"
                        placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $users->position }}" name="position"
                        placeholder="Jabatan" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $users->no_hp }}" name="no_hp"
                        placeholder="Jabatan" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
                </div>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="photo" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
