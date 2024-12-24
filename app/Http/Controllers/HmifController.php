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
        // Validasi data dari form
        $validatedData = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_ruangan' => 'required|integer|exists:ruangan,room_id',
            'id_peminjam' => 'required|integer|exists:users,id',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
            'keterangan_peminjaman' => 'nullable|string|max:255',
        ]);
    
        // Simpan file surat peminjaman
        $filePath = $request->file('surat_peminjaman')->store('surat_peminjaman', 'public');
    
        // Simpan data ke tabel `peminjaman_ruangan`
        DB::table('peminjaman_ruangan')->insert([
            'id_ruangan' => $validatedData['id_ruangan'],
            'id_peminjam' => $validatedData['id_peminjam'],
            'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'surat_peminjaman' => $filePath,
            'keterangan_peminjaman' => $validatedData['keterangan_peminjaman'],
            'status' => 'sedang diajukan'
        ]);
    
        return redirect()->route('statusPemRuangan')->with('success', 'Pengajuan ruangan berhasil disimpan');
    }
}