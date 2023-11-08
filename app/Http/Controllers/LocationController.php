<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::paginate(10);
        return view('konfigurasi.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $office_location = $request->office_location;
        $radius = $request->radius;

        try {
            $location = new Location; // Buat instance model User

            $location->office_location = $office_location;
            $location->radius = $radius;

            $location->save(); // Simpan data ke database

            return redirect()->back()->with(['success' => 'Data Berhasil Di Simpan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $location = DB::table('location_configuration')->where('id', $id)->first();
        return view('konfigurasi.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {

        // Cari user berdasarkan NIK
        $location = Location::where('id', $id)->first();

        if (!$location) {
            // Handle jika user tidak ditemukan
            return redirect()->route('location.config')->with('error', 'Lokasi tidak ditemukan.');
        }

        // Update data user berdasarkan input form
        $location->office_location = $request->input('office_location');
        $location->radius = $request->input('radius');

        // Simpan perubahan data
        $location->save();

        return redirect()->route('location.config')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $delete = DB::table('location_configuration')->where('id', $id)->delete();
        if($delete){
            return redirect()->back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
