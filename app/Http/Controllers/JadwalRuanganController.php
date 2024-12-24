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
    public function index()
    {
        // Mengambil semua jadwal ruangan beserta data ruangan yang terkait
        $jadwalRuangan = JadwalRuangan::with('ruangan')->get();

        return view('hmif.jadwalRuangan', compact('jadwalRuangan'));
    }

}
