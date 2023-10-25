<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\UserController;

require __DIR__.'/auth.php';


Route::middleware(['role:admin', 'auth'])->group(function () {
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('dashboard.admin');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/edit/{nik}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete/{nik}', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/presensi-monitoring', [PresensiController::class, 'monitoring'])->name('presensi.monitoring');
    Route::post('/getpresensi', [PresensiController::class, 'presensi'])->name('get.presensi');
});


Route::middleware(['role:guest','auth'])->group(function () {
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



Route::get('/', function () { return view('auth.login'); });

