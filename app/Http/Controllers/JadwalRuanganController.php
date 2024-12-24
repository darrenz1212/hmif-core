<?php

namespace App\Http\Controllers;

use App\Models\JadwalRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalRuanganController extends Controller
{
    /**
     * Menampilkan daftar jadwal ruangan.
     */
//    public function index()
//    {
//        // Mengambil semua jadwal ruangan beserta data ruangan yang terkait
//        $jadwalRuangan = JadwalRuangan::with('ruangan')->get();
//
//        return view('hmif.jadwalRuangan', compact('jadwalRuangan'));
//    }

    public function index()
    {
        $jadwal = JadwalRuangan::with('ruangan')->get();

        // Konversi data ke format FullCalendar
        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->keterangan,
                'start' => $item->tanggal . 'T' . $item->jam_mulai,
                'end' => $item->tanggal . 'T' . $item->jam_selesai,
                'description' => $item->ruangan->nama ?? 'Unknown Room',
            ];
        });

        return response()->json($events);
    }
}
