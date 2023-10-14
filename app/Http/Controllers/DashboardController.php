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
        $bulanIni = date("m");
        $tahunIni = date("Y");
        $email = Auth::guard('web')->user()->email;
        $presensiHariIni = DB::table('presensi')->where('email',$email)->where('attendance_date',$hariIni)->first();
        $historiBulanIni = DB::table('presensi')->whereRaw('MONTH(attendance_date)="'.$bulanIni.'"')
            ->whereRaw('YEAR(attendance_date)="'.$tahunIni.'"')
            ->orderBy('attendance_date')
            ->get();
        return view('dashboard.dashboard', compact('presensiHariIni','historiBulanIni'));
    }

    // public function index() 
    // {
    //     return view('dashboard.dashboard');
    // }


}
