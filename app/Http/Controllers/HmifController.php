<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomController;

class HmifController extends Controller
{
    public function index()
    {
        return view('hmif.dashboard');
    }

//    Fetching status peminjaman from getStatusPeminjaman in PeminjamanController
    public function statusPemRuangan()
    {
        $peminjaman = new PeminjamanController();
        $peminjamanRuangan = $peminjaman->getStatusPeminjaman();

        return view('hmif.statusPemRuangan', ['peminjamanRuangan' => $peminjamanRuangan]);
    }

//    Fetching jadwal Ruangan from getJadwalRuangan function in RoomController
    public function jadwalRuangan()
    {
        // Render halaman kalender
        return view('hmif.jadwalRuangan');
    }

    public function getJadwalRuangan()
    {
        // Ambil data jadwal dari RoomController
        $roomController = new RoomController();
        $jadwalRuangan = $roomController->getJadwalRuangan();

        // Kembalikan data dalam format JSON untuk FullCalendar
        return response()->json($jadwalRuangan);
    }

    public function getRuangan()
    {
        $getRuangan = new RoomController();
        $ruangan = $getRuangan ->getAllRoom();

        return response()->json($ruangan);
    }

    public function getJadwalRuanganByRoom(Request $request)
    {
        $roomId = $request->query('room_id');

        $roomController = new RoomController();
        $jadwalRuangan = $roomController->getJadwalRuangan();
        if ($roomId) {
            $jadwalRuanganItem = $jadwalRuangan->filter(function ($item) use ($roomId) {
                return $item['room_id'] == $roomId;
            });
        }

        return response()->json($jadwalRuanganItem->values());
//        return dd($jadwalRuanganItem);
    }



    public function pengajuanRuangan()
    {
        $ruanganList = Ruangan::all();

        $userList = User::all();

        return view('hmif.pengajuanRuangan', [
            'ruanganList' => $ruanganList,
            'userList' => $userList,
        ]);
    }

    public function submitPengajuanRuangan(Request $request)
    {
        $validatedData = $request->validate([
            'id_ruangan' => 'required|integer',
            'id_peminjam' => 'required|integer',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
            'keterangan_peminjaman' => 'nullable|string|max:255',
            'tanggal_peminjaman' => 'required|date',
            'waktu_peminjaman' => 'required',
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
            'status' => 'sedang diajukan',
        ]);

        return redirect()->back()->with('success', 'Pengajuan ruangan berhasil disimpan.');
    }



}
