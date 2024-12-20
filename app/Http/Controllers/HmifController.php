<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalRuangan;

class HmifController extends Controller
{
    public function index()
    {
        return view('hmif.dashboard');
    }

    // Fetching status peminjaman from PeminjamanController
    public function statusPemRuangan()
    {
        $peminjaman = new PeminjamanController();
        $peminjamanRuangan = $peminjaman->getStatusPeminjaman();

        return view('hmif.statusPemRuangan', ['peminjamanRuangan' => $peminjamanRuangan]);
    }

    // Fetching jadwal Ruangan from RoomController
    public function jadwalRuangan()
    {
        $roomController = new RoomController();
        $jadwalRuangan = $roomController->getJadwalRuangan();
        return view('hmif.jadwalRuangan', ['jadwalRuangan' => $jadwalRuangan]);
    }

    public function pengajuanRuangan()
    {
        $userList = User::all();

        return view('hmif.pengajuanRuangan', [
            'userList' => $userList,
        ]);
    }

    public function submitPengajuanRuangan(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_ruangan' => 'required|integer|exists:ruangan,room_id',
            'id_peminjam' => 'required|integer|exists:users,id',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
            'keterangan_peminjaman' => 'nullable|string|max:255',
        ]);
    
        $filePath = $request->file('surat_peminjaman')->store('surat_peminjaman', 'public');
    
        // Insert data into the `jadwal_ruangan` table
        JadwalRuangan::create([
            'room_id' => $validatedData['id_ruangan'],
            'tanggal' => $validatedData['tanggal_peminjaman'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'keterangan' => $validatedData['keterangan_peminjaman'],
        ]);
    
        return redirect()->back()->with('success', 'Pengajuan ruangan berhasil disimpan.');
    }
    

}
