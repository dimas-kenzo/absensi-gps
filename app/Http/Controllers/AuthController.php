<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);
        
        // Coba login pengguna
        $user = Auth::guard('pegawai')->attempt($credentials);
        if ($user) {
            echo 'y';
        } else {
            echo 'x';
        }
    }
}
