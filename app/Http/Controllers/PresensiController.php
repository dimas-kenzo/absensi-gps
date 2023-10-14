<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $email = Auth::guard('web')->user()->email;
        $today = date('Y-m-d');
        $check = DB::table('presensi')->where('attendance_date', $today)->where('email', $email)->count();
        return view('presensi.create', compact('check'));
    }

    public function store(Request $request)
    {
        $email = Auth::guard('web')->user()->email;
        $attendance_date = date('Y-m-d');
        $jam = date('H:i:s');
        $latitudeKantor = -7.6706688716518;
        $longitudeKantor = 109.66083880761339;
        // $latitudeKantor = -7.625701513405246;
        // $longitudeKantor = 109.58423696713866;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(',', $lokasi);
        $latitudeUser = $lokasiuser[0];
        $longitudeUser = $lokasiuser[1];
        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak['meters']);

        $check = DB::table('presensi')->where('attendance_date', $attendance_date)->where('email', $email)->count();
        if($check > 0){
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = 'public/uploads/absensi/';
        $formatName = $email . "-" . $attendance_date . "-" .$ket;
        $image_parts = explode(';base64', $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius > 65) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor|radius";
        } else {
            if ($check > 0) {
                $data_pulang = [
                    'check_out_time' => $jam,
                    'photo_out' => $fileName,
                    'location_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('attendance_date', $attendance_date)->where('email', $email)->update($data_pulang);
                if ($update) {
                    echo 'success|Terima Kasih, Hati-hati Di Jalan|out';
                    Storage::put($file, $image_base64);
                } else {
                    echo 'error|Maaf, silakan coba lagi|out';
                }
            } else {
                $data = [
                    'email' => $email,
                    'attendance_date' => $attendance_date,
                    'check_in_time' => $jam,
                    'photo_in' => $fileName,
                    'location_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo 'success|Terima Kasih, Selamat Bekerja|in';
                    Storage::put($file, $image_base64);
                } else {
                    echo 'error|Maaf, silakan coba lagi|in';
                }
            }
        }
    }

    



    //Menghitung Jarak
    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
}
