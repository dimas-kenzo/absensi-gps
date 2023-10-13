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
        return view('presensi.create');
    }

    public function store(Request $request){
        $email = Auth::guard('web')->user()->email;
        $attendance_date = date('Y-m-d');
        $jam = date('H:i:s');
        $location = $request->location;
        $image = $request->image;
        $folderPath = 'public/uploads/absensi/';
        $formatName = $email."-".$attendance_date;
        $image_parts = explode(';base64',$image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName.".png";
        $file = $folderPath.$fileName;
        $data = [
            'email' => $email,
            'attendance_date' => $attendance_date,
            'check_in_time' => $jam,
            'photo_in' => $fileName,
            'location_in' => $location
        ];
        $simpan = DB::table('presensi')->insert($data);
        if($simpan){
            echo '0';
            Storage::put($file,$image_base64);
        } else {
            echo '1';
        }
    }
}
