<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $query->select('users.*', 'position');
        $query->orderBy('name');
        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if (!empty($request->position)) {
            $query->where('position', $request->position);
        }
        $users = $query->paginate(10);
        $positions = DB::table('users')->orderBy('position')->get();
        return view('users.index', compact('users', 'positions'));
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        $no_hp = $request->no_hp;
        $position = $request->position;

        if ($request->hasFile('photo')) {
            $photo = $nik . "." . $request->file('photo')->getClientOriginalExtension();
        } else {
            $photo = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'no_hp' => $no_hp,
                'position' => $position,
                'photo' => $photo,
                'role_id' => 2,
            ];

            $simpan = DB::table('users')->insert($data);
            if ($simpan) {
                if ($request->hasFile('photo')) {
                    $folderPath = "public/uploads/users/";
                    $request->file('photo')->storeAs($folderPath, $photo);
                }
                return redirect()->back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {
        $nik = $request->nik;
        $user = DB::table('users')->where('nik', $nik)->first();
        return view('users.edit', compact('user'));
    }

    // public function update(Request $request, $nik)
    // {
    //     $nik = $request->nik;
    //     $name = $request->name;
    //     $email = $request->email;
    //     $no_hp = $request->no_hp;
    //     $position = $request->position;

    //     // Mungkin Anda ingin melakukan validasi tambahan di sini sebelum melanjutkan

    //     $data = [
    //         'nik' => $nik,
    //         'name' => $name,
    //         'email' => $email,
    //         'no_hp' => $no_hp,
    //         'position' => $position,
    //     ];

    //     if ($request->hasFile('photo')) {
    //         $photo = $nik . "." . $request->file('photo')->getClientOriginalExtension();
    //         $data['photo'] = $photo;

    //         $folderPath = "public/uploads/users/";

    //         // Simpan berkas foto baru
    //         $request->file('photo')->storeAs($folderPath, $photo);
    //     }

    //     try {
    //         $update = DB::table('users')
    //             ->where('nik', $nik)
    //             ->update($data);

    //         if ($update) {
    //             return redirect()->back()->with(['success' => 'Data Berhasil Diperbarui']);
    //         }
    //     } catch (\Exception $e) {
    //         // dd($e);
    //         return redirect()->back()->with(['warning' => 'Data Gagal Diperbarui']);
    //     }
    // }

    public function update(Request $request, $nik)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'position' => 'required',
            'no_hp' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nik' => 'required|numeric', // Validasi NIK
            'password' => 'nullable|min:6', // Validasi Password (opsional)
        ]);

        // Cari user berdasarkan NIK
        $user = User::where('nik', $nik)->first();

        if (!$user) {
            // Handle jika user tidak ditemukan
            return redirect()->route('users.index')->with('error', 'User tidak ditemukan.');
        }

        // Update data user berdasarkan input form
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->position = $request->input('position');
        $user->no_hp = $request->input('no_hp');
        $user->nik = $request->input('nik'); // Update NIK

        // Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // if ($request->hasFile('photo')) {
        //     $photo = $request->file('photo');
        //     $fileName = $photo->getClientOriginalName();
        //     $photo->storeAs('uploads/users', $fileName, 'public');
        //     $user->photo = $fileName;
        // }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = $nik . '.' . $photo->getClientOriginalExtension(); // Menggunakan ekstensi file yang diunggah
        
            // Menyimpan foto ke direktori yang sesuai
            $photo->storeAs('uploads/users', $fileName, 'public');
        
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete('public/uploads/users/' . $user->photo);
            }
        
            $user->photo = $fileName;
        }
        

        // Simpan perubahan data
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($nik)
    {
        $delete = DB::table('users')->where('nik', $nik)->delete();
        if($delete){
            return redirect()->back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
