<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

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
        // $latitudeKantor = -7.6706688716518;
        // $longitudeKantor = 109.66083880761339;
        $latitudeKantor = -7.625701513405246;
        $longitudeKantor = 109.58423696713866;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(',', $lokasi);
        $latitudeUser = $lokasiuser[0];
        $longitudeUser = $lokasiuser[1];
        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak['meters']);

        $check = DB::table('presensi')->where('attendance_date', $attendance_date)->where('email', $email)->count();
        if ($check > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = 'public/uploads/absensi/';
        $formatName = $email . "-" . $attendance_date . "-" . $ket;
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

    public function editProfile()
    {
        $email = Auth::guard('web')->user()->email;
        $users = DB::table('users')->where('email', $email)->first();
        return view('presensi.editProfile', compact('users'));
    }

    public function updateProfile(Request $request)
    {
        $email = Auth::guard('web')->user()->email;
        $name = $request->name;
        $position = $request->position;
        $position = $request->position;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);

        $user = DB::table('users')->where('email', $email)->first();

        if($request->hasFile('photo')){
            $photo = $email.".".$request->file('photo')->getClientOriginalExtension();
        } else {
            $photo = $user->photo;
        }

        if (empty($request->password)) {
            $data = [
                'name' => $name,
                'position' => $position,
                'no_hp' => $no_hp,
                'photo' => $photo
            ];
        } else {
            $data = [
                'name' => $name,
                'position' => $position,
                'no_hp' => $no_hp,
                'password' => $password,
                'photo' => $photo
            ];
        }

        $update = DB::table('users')->where('email', $email)->update($data);

        // if ($update) {
        //     return redirect()->route('dashboard');
        // } else {
        //     return redirect()->back();
        // }

        if ($update) {
            if($request->hasFile('photo')){
                $folderPath = "public/uploads/users/";
                $request->file('photo')->storeAs($folderPath, $photo);
            }
            return redirect()->back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect()->back()->with(['error' => 'Data Gagal Di Update']);
        }

    }

    // public function updateProfile(Request $request)
    // {
    //     $email = Auth::guard('web')->user()->email;
    //     $name = $request->name;
    //     $position = $request->position;
    //     $no_hp = $request->no_hp;
    //     $password = $request->password; // Ambil password dari permintaan tanpa hashing.

    //     $data = [
    //         'name' => $name,
    //         'position' => $position,
    //         'no_hp' => $no_hp,
    //     ];

    //     if (!empty($password)) {
    //         $data['password'] = Hash::make($password); // Hanya hash password jika ada perubahan.
    //     }

    //     $update = DB::table('users')->where('email', $email)->update($data);

    //     if ($update) {
    //         return redirect()->back()->with(['success' => 'Data Berhasil Di Update']);
    //     } else {
    //         return redirect()->back()->with(['error' => 'Data Gagal Di Update']);
    //     }
    // }

    public function history() {
        $namaBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view ('presensi.history', compact('namaBulan'));
    }

    public function getHistory(Request $request) {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $email = Auth::guard('web')->user()->email;

        $history = DB::table('presensi')
            ->whereRaw('MONTH(attendance_date)="'.$bulan.'"')
            ->whereRaw('YEAR(attendance_date)="'.$tahun.'"')
            ->where('email',$email)
            ->orderBy('attendance_date')
            ->get();

        return view('presensi.gethistory', compact('history'));
    }

    public function izin() 
    {
        $user_id = Auth::id();
        $dataizin = DB::table('izin')->where('user_id', $user_id)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatIzin()
    {
        return view('presensi.buatizin');
    }

    public function storeIzin(Request $request)
    {
        $user_id = Auth::id();
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'user_id' => $user_id,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('izin')->insert($data);

        if($simpan){
            return redirect()->route('presensi.izin')->with('success','Data Berhasil Di Simpan');
        } else {
            return redirect()->route('presensi.buatizin')->with('error','Data Gagal Di Simpan');
        }
    }
}
