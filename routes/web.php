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
Route::get('klb-room',[KalabController::class,'showAllRoom'])->name('kalab-showroom');
Route::post('klb/rooms', [KalabController::class, 'createRoom'])->name('rooms.store');
Route::put('klb/rooms/{id}', [KalabController::class, 'updateRoom'])->name('rooms.update');
Route::delete('klb/rooms/{id}', [KalabController::class, 'deleteRoom'])->name('rooms.destroy');


Route::get('klb/inventaris',[KalabController::class, 'showInventaris'])->name('inventory');
Route::post('klb/store', [KalabController::class, 'createInventaris'])->name('inventory.store');
Route::put('klb/update/{id}', [KalabController::class, 'updateInventaris'])->name('inventory.update');
Route::delete('klb/delete/{id}', [KalabController::class, 'deleteInventaris'])->name('inventory.destroy');
//====================================================== KALAB SIDE END ======================================================


//======================================================   HMIF SIDE   ======================================================
Route::get('hmif-dash',[HmifController::class,'index'])->name('hmif-dashboard');
Route::get('hmif/pengajuanRuangan', [HmifController::class, 'pengajuanRuangan'])->name('pengajuanRuangan');
Route::post('hmif/pengajuanRuangan', [HmifController::class, 'submitPengajuanRuangan'])->name('submitPengajuanRuangan');
Route::get('/hmif/statusPemRuangan', [HmifController::class, 'statusPemRuangan'])->name('statusPemRuangan');
Route::get('/hmif/ketersediaanRuangan', [RoomController::class,'showRoomHima'])->name('ketersediaanRuangan');
Route::get('/hmif/ketersediaanRuangan/{id}/info', [RoomController::class, 'getRoomInfo'])->name('ruangan.info');
Route::get('/hmif/jadwalRuangan', [HmifController::class, 'jadwalRuangan'])->name('jadwalRuangan');
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
