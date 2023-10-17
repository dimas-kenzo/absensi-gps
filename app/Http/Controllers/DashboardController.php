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

        $historiBulanIni = DB::table('presensi')
            ->where('email',$email)
            ->whereRaw('MONTH(attendance_date)="'.$bulanIni.'"')
            ->whereRaw('YEAR(attendance_date)="'.$tahunIni.'"')
            ->orderBy('attendance_date')
            ->get();

        $rekapPresensi = DB::table('presensi')
            ->selectRaw('COUNT(email) as jmlHadir, SUM(IF(check_in_time > "07.00",1,0)) as jmlTelat')
            ->where('email',$email)
            ->whereRaw('MONTH(attendance_date)="'.$bulanIni.'"')
            ->whereRaw('YEAR(attendance_date)="'.$tahunIni.'"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('users','presensi.email','=','users.email')
            ->where('attendance_date', $hariIni)
            ->orderBy('check_in_time')
            ->get();

        $namaBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('dashboard.dashboard', compact('presensiHariIni', 'historiBulanIni', 'namaBulan', 'bulanIni', 'tahunIni', 'rekapPresensi', 'leaderboard'));
    }

    // public function index() 
    // {
    //     return view('dashboard.dashboard');
    // }


}
