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

    public function statusPemRuangan()
    {
        $peminjamanRuangan = DB::table('peminjaman_ruangan')
            ->select(
                'peminjaman_ruangan.id_peminjaman_ruangan',
                'peminjaman_ruangan.surat_peminjaman',
                'peminjaman_ruangan.keterangan_peminjaman',
                'peminjaman_ruangan.tanggal_peminjaman',
                'peminjaman_ruangan.waktu_peminjaman',
                'peminjaman_ruangan.status',
                'users.name as nama_peminjam',
                'ruangan.nama_ruangan'
            )
            ->join('users', 'peminjaman_ruangan.id_peminjam', '=', 'users.id')
            ->join('ruangan', 'peminjaman_ruangan.id_ruangan', '=', 'ruangan.room_id')
            ->get();


        return view('hmif.statusPemRuangan', ['peminjamanRuangan' => $peminjamanRuangan]);

//        dd($peminjamanRuangan);
    }

    public function jadwalRuangan()
    {
        $jadwalRuangan = DB::table('jadwal_ruangan')
            ->join('ruangan', 'jadwal_ruangan.room_id', '=', 'ruangan.room_id')
            ->select(
                'jadwal_ruangan.*',
                'ruangan.nama_ruangan'
            )
            ->get();

        return view('hmif.jadwalRuangan', ['jadwalRuangan' => $jadwalRuangan]);

//        return dd($jadwalRuangan);
    }

    public function ketersediaanRuangan()
    {
        $availableRooms = Ruangan::where('ketersediaan', 1)->get();
        return view('hmif.ketersediaanRuangan', ['availableRooms' => $availableRooms]);
    }


    public function pengajuanRuangan()
    {
        $ruanganList = Ruangan::where('ketersediaan', 1)->get();

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
