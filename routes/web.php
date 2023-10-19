<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;


require __DIR__.'/auth.php';



// Route::get('/panel', function () { return view ('auth.loginadmin'); });
Route::middleware(['role:admin', 'auth', 'verified'])->group(function () {
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('dashboard.admin');
});

Route::get('/', function () { return view('auth.login'); });
Route::middleware(['role:guest', 'auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/presensi/edit', [PresensiController::class, 'editProfile'])->name('presensi.edit');
    Route::post('/presensi/{id}/updateProfile', [PresensiController::class, 'updateProfile'])->name('presensi.update');
    Route::get('/presensi/history', [PresensiController::class, 'history'])->name('presensi.history');
    Route::post('/gethistori', [PresensiController::class, 'getHistory'])->name('presensi.gethistory');
    Route::get('/presensi/izin', [PresensiController::class, 'izin'])->name('presensi.izin');
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatIzin'])->name('presensi.buatizin');
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeIzin'])->name('presensi.storeizin');
});

