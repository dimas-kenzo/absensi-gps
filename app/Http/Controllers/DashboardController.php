<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() 
    {
        $hariIni = date("Y-m-d");
        $email = Auth::guard('web')->user()->email;
        $presensiHariIni = DB::table('presensi')->where('email',$email)->where('attendance_date',$hariIni)->first();
        return view('dashboard.dashboard', compact('presensiHariIni'));
    }

    // public function index() 
    // {
    //     return view('dashboard.dashboard');
    // }


}
