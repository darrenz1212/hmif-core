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

Route::get('klb/jadwalruangan',[KalabController::class, 'jadwalRuangan'])->name('klb.jadwalRuangan');
Route::post('klb/addJadwal', [KalabController::class, 'createJadwal'])->name('jadwalRuangan.store');

Route::get('klb/showPengajuan',[KalabController::class, 'showPengajuan'])->name('klb.showPengajuan');
Route::post('klb/peminjaman/{id}/approve', [KalabController::class, 'aprrovePengajuan'])->name('peminjaman.approve');
Route::post('klb/peminjaman/{id}/decline', [KalabController::class, 'declinePengajuan'])->name('peminjaman.decline');


Route::get('/klb/api/jadwalRuangan',[KalabController::class, 'getJadwalRuangan'])->name('klbapi.jadwalRuangan');
Route::get('/klb/api/ruangan', [KalabController::class, 'getRuangan'])->name('klbapi.ruangan');
Route::get('/klb/api/jadwalRuanganByRoom', [KalabController::class, 'getJadwalRuanganByRoom'])->name('klbapi.jadwalRuanganByRoom');
//====================================================== KALAB SIDE END ======================================================


//======================================================   HMIF SIDE   ======================================================
Route::get('hmif-dash',[HmifController::class,'index'])->name('hmif-dashboard');
Route::get('hmif/pengajuanRuangan', [HmifController::class, 'pengajuanRuangan'])->name('pengajuanRuangan');
Route::post('hmif/pengajuanRuangan', [HmifController::class, 'submitPengajuanRuangan'])->name('submitPengajuanRuangan');
Route::get('/hmif/statusPemRuangan', [HmifController::class, 'statusPemRuangan'])->name('statusPemRuangan');
Route::post('/hmif/ketersediaanRuangan', [RoomController::class, 'getAvailableRooms'])->name('ketersediaanRuangan');

Route::get('/hmif/ketersediaanRuangan/{id}/info', [RoomController::class, 'getRoomInfo'])->name('ruangan.info');
Route::get('/hmif/jadwalRuangan', [HmifController::class, 'jadwalRuangan'])->name('jadwalRuangan');

Route::get('/hmif/api/jadwalRuangan',[HmifController::class, 'getJadwalRuangan'])->name('api.jadwalRuangan');
Route::get('/hmif/api/ruangan', [HmifController::class, 'getRuangan'])->name('api.ruangan');
Route::get('/hmif/api/jadwalRuanganByRoom', [HmifController::class, 'getJadwalRuanganByRoom'])->name('api.jadwalRuanganByRoom');

Route::get('/hmif/roomsFacilities', [HmifController::class, 'showAllRoomFacilities'])->name('hmif.fasilitas');

Route::get('/hmif/peminjamanBarang', [HmifController::class, 'viewPeminjamanBarang'])->name('hmif.PemBarang');
Route::post('/hmif/peminjamanBarang', [HmifController::class, 'storePeminjamanBarang'])->name('hmif.storePemBarang');
//====================================================== HMIF SIDE END ======================================================


//======================================================   STAFF SIDE   ======================================================
Route::get('/stafflab/dashboard',[StaffController::class,'index'])->name('stafflab-dashboard');


Route::post('/stafflab/roomsFacilities/{room_id}', [StaffController::class, 'storeFacility'])->name('stafflab.storeFacility');

Route::get('/stafflab/roomsFacilities', [StaffController::class, 'showAllRoomFacilities'])->name('stafflab.roomFacilities');
Route::get('/stafflab/roomsFacilities/{id}/edit', [StaffController::class, 'editRoomFacilities'])->name('stafflab.editFacilities');
Route::put('/stafflab/roomsFacilities/{id}', [StaffController::class, 'updateRoomFacilities'])->name('stafflab.updateFacilities');

Route::get('/stafflab/rooms', [StaffController::class, 'showAllRoom'])->name('stafflab.rooms');
Route::post('/stafflab/rooms', [StaffController::class, 'storeRooms'])->name('stafflab.storeRooms');
Route::put('/stafflab/rooms/{id}', [StaffController::class, 'updateRooms'])->name('stafflab.updateRooms');

Route::get('/stafflab/inventory', [StaffController::class, 'inventory'])->name('stafflab.inventory');
Route::post('/stafflab/store', [StaffController::class, 'storeInventory'])->name('stafflab.storeInventory');
Route::put('/stafflab/update', [StaffController::class, 'updateInventory'])->name('stafflab.updateInventory');
//====================================================== STAFF SIDE END ======================================================

//====================================================== Debugging Session ======================================================
Route::get('/debug/api/jadwalRuanganByRoom/{roomId}',[HmifController::class, 'getJadwalRuanganByRoom'])->name('debug.jadwalRuangan');
Route::get('debug/peminjamanApp/15',[\App\Http\Controllers\PeminjamanController::class, 'approved']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
