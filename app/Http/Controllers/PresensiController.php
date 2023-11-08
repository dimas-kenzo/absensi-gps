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
        $office_loc = DB::table('location_configuration')->where('id', 1)->first();
        return view('presensi.create', compact('check','office_loc'));
    }

    public function store(Request $request)
    {
        $email = Auth::guard('web')->user()->email;
        $attendance_date = date('Y-m-d');
        $jam = date('H:i:s');
        $office_loc = DB::table('location_configuration')->where('id', 1)->first();
        $loc = explode(','  , $office_loc->office_location);
        $latitudeKantor = $loc[0];
        $longitudeKantor = $loc[1];
        // $latitudeKantor = -7.625701513405246;
        // $longitudeKantor = 109.58423696713866;
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

        if ($radius > $office_loc->radius) {
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

        if ($request->hasFile('photo')) {
            $photo = $email . "." . $request->file('photo')->getClientOriginalExtension();
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
            if ($request->hasFile('photo')) {
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

    public function history()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.history', compact('namaBulan'));
    }

    public function getHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $email = Auth::guard('web')->user()->email;

        $history = DB::table('presensi')
            ->whereRaw('MONTH(attendance_date)="' . $bulan . '"')
            ->whereRaw('YEAR(attendance_date)="' . $tahun . '"')
            ->where('email', $email)
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

        if ($simpan) {
            return redirect()->route('presensi.izin')->with('success', 'Data Berhasil Di Simpan');
        } else {
            return redirect()->route('presensi.buatizin')->with('error', 'Data Gagal Di Simpan');
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function presensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
        ->select('presensi.*', 'users.nik', 'users.name','users.position')
            ->join('users', 'presensi.email', '=', 'users.email')
            ->where('presensi.attendance_date', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampil(Request $request)
    {
        $id = $request->id;
        
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'users.name')
            ->join('users', 'presensi.email', '=', 'users.email')
            ->where('presensi.id', $id)
            ->first();
    
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $users = DB::table('users')->orderBy('name')->get();
        return view('presensi.laporan', compact('namaBulan', 'users'));
    }

    public function cetak(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $user = DB::table('users')->where('nik', $nik)->first();
    
        $presensi = DB::table('presensi')
            ->where('email', $user->email) // Menggunakan email dari user yang memiliki nik yang sesuai
            ->whereRaw('MONTH(attendance_date)="' . $bulan . '"')
            ->whereRaw('YEAR(attendance_date)="' . $tahun . '"')
            ->orderBy('attendance_date')
            ->get();
    
        // dd($presensi);
    
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
        return view('presensi.cetak', compact('namaBulan','bulan','tahun','user','presensi'));
    }
    

    public function rekap()
    {
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namaBulan'));
    }

    public function cetakRekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
        ->selectRaw('users.nik, users.name,
            MAX(IF(DAY(attendance_date) = 1,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_1,
            MAX(IF(DAY(attendance_date) = 2,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_2,
            MAX(IF(DAY(attendance_date) = 3,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_3,
            MAX(IF(DAY(attendance_date) = 4,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_4,
            MAX(IF(DAY(attendance_date) = 5,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_5,
            MAX(IF(DAY(attendance_date) = 6,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_6,
            MAX(IF(DAY(attendance_date) = 7,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_7,
            MAX(IF(DAY(attendance_date) = 8,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_8,
            MAX(IF(DAY(attendance_date) = 9,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_9,
            MAX(IF(DAY(attendance_date) = 10,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_10,
            MAX(IF(DAY(attendance_date) = 11,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_11,
            MAX(IF(DAY(attendance_date) = 12,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_12,
            MAX(IF(DAY(attendance_date) = 13,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_13,
            MAX(IF(DAY(attendance_date) = 14,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_14,
            MAX(IF(DAY(attendance_date) = 15,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_15,
            MAX(IF(DAY(attendance_date) = 16,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_16,
            MAX(IF(DAY(attendance_date) = 17,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_17,
            MAX(IF(DAY(attendance_date) = 18,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_18,
            MAX(IF(DAY(attendance_date) = 19,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_19,
            MAX(IF(DAY(attendance_date) = 20,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_20,
            MAX(IF(DAY(attendance_date) = 21,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_21,
            MAX(IF(DAY(attendance_date) = 22,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_22,
            MAX(IF(DAY(attendance_date) = 23,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_23,
            MAX(IF(DAY(attendance_date) = 24,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_24,
            MAX(IF(DAY(attendance_date) = 25,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_25,
            MAX(IF(DAY(attendance_date) = 26,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_26,
            MAX(IF(DAY(attendance_date) = 27,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_27,
            MAX(IF(DAY(attendance_date) = 28,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_28,
            MAX(IF(DAY(attendance_date) = 29,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_29,
            MAX(IF(DAY(attendance_date) = 30,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_30,
            MAX(IF(DAY(attendance_date) = 31,CONCAT(check_in_time,"-",IFNULL(check_out_time,"00.00.00")),"" )) as tgl_31')
        ->join('users', 'presensi.email', '=', 'users.email')
        ->whereRaw('MONTH(attendance_date)="'.$bulan.'"')
        ->whereRaw('YEAR(attendance_date)="'.$tahun.'"')
        ->groupBy('users.nik', 'users.name')
        ->get();

        // dd($rekap);
        return view ('presensi.cetakRekap', compact('bulan','tahun','rekap','namaBulan'));
    }
    
}
