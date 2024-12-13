<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            'waktu_peminjaman' => 'required|date_format:H:i',
            'tanggal_batas' => 'required|date|after_or_equal:tanggal_peminjaman',
            'waktu_batas' => 'required|date_format:H:i',
            'id_ruangan' => 'required|integer|exists:ruangan,room_id',
            'id_peminjam' => 'required|integer|exists:users,id',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
            'keterangan_peminjaman' => 'nullable|string|max:255',
        ]);

        $filePath = $request->file('surat_peminjaman')->store('surat_peminjaman', 'public');

        // Insert data into the database
        DB::table('peminjaman_ruangan')->insert([
            'id_ruangan' => $validatedData['id_ruangan'],
            'id_peminjam' => $validatedData['id_peminjam'],
            'surat_peminjaman' => $filePath,
            'keterangan_peminjaman' => $validatedData['keterangan_peminjaman'],
            'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
            'waktu_peminjaman' => $validatedData['waktu_peminjaman'],
            'batas_tanggal' => $validatedData['tanggal_batas'],
            'batas_waktu' => $validatedData['waktu_batas'],
            'status' => 'sedang diajukan',
        ]);

        return redirect()->back()->with('success', 'Pengajuan ruangan berhasil disimpan.');
    }

    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'waktu_peminjaman' => 'required|date_format:H:i',
            'tanggal_batas' => 'required|date|after_or_equal:tanggal_peminjaman',
            'waktu_batas' => 'required|date_format:H:i',
        ]);

        // Fetch all rooms
        $allRooms = DB::table('ruangan')->get();

        // Filter rooms that are not booked during the requested time range
        $availableRooms = $allRooms->filter(function ($room) use ($request) {
            $isRoomBooked = DB::table('peminjaman_ruangan')
                ->where('id_ruangan', $room->room_id)
                ->where(function ($query) use ($request) {
                    $query->whereDate('tanggal_peminjaman', '<=', $request->tanggal_batas)
                          ->whereDate('batas_tanggal', '>=', $request->tanggal_peminjaman)
                          ->whereTime('waktu_peminjaman', '<=', $request->waktu_batas)
                          ->whereTime('batas_waktu', '>=', $request->waktu_peminjaman);
                })
                ->exists();

            return !$isRoomBooked;
        });

        return view('hmif.ketersediaanRuangan', [
            'availableRooms' => $availableRooms,
            'requestData' => $request->all(),
        ]);
    }
}
