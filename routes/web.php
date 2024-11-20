<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KalabController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\HmifController;
use App\Http\Controllers\RoomController;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//======================================================   KALAB SIDE   ======================================================
Route::get('klb-dash',[KalabController::class,'index'])->name('kalab-dashboard');
Route::get('klb-room',[RoomController::class,'showAllRoom'])->name('kalab-showroom');
//====================================================== KALAB SIDE END ======================================================

//======================================================   HMIF SIDE   ======================================================
Route::get('hmif-dash',[HmifController::class,'index'])->name('hmif-dashboard');
Route::get('hmif/pengajuanRuangan', [HmifController::class, 'pengajuanRuangan'])->name('pengajuanRuangan');
Route::post('hmif/pengajuanRuangan', [HmifController::class, 'submitPengajuanRuangan'])->name('submitPengajuanRuangan');
Route::get('/hmif/statusPemRuangan', [HmifController::class, 'statusPemRuangan'])->name('statusPemRuangan');
Route::get('/hmif/ketersediaanRuangan', [HmifController::class, 'ketersediaanRuangan'])->name('ketersediaanRuangan');
Route::get('/hmif/jadwalRuangan', [HmifController::class, 'index'])->name('jadwalRuangan');

//====================================================== HMIF SIDE END ======================================================

//======================================================   STAFF SIDE   ======================================================
Route::get('staff-dash',[StaffController::class,'index'])->name('staff-dashboard');
//====================================================== STAFF SIDE END ======================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
