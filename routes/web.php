<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KalabController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\HmifController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//======================================================   KALAB SIDE   ======================================================
Route::get('klb-dash',[KalabController::class,'index'])->name('kalab-dashboard');
//====================================================== KALAB SIDE END ======================================================

//======================================================   HMIF SIDE   ======================================================
Route::get('hmif-dash',[HmifController::class,'index'])->name('hmif-dashboard');
//====================================================== HMIF SIDE END ======================================================

//======================================================   STAFF SIDE   ======================================================
Route::get('staff-dash',[StaffController::class,'index'])->name('staff-dashboard');
//====================================================== STAFF SIDE END ======================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->middleware('role:hmif');
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->middleware('role:hmif');
    Route::get('/peminjaman/approve', [PeminjamanController::class, 'approve'])->middleware('role:kalab');
});

require __DIR__.'/auth.php';
