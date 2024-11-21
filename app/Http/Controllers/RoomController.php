<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function showAllRoom()
    {
        $ruangan = Ruangan::all();

        return view('kalab.room-view', [
            'ruangan'=> $ruangan
        ]);
    }

    public function getInfo($id)
    {
        // Cari ruangan berdasarkan ID
        $ruangan = Ruangan::with('fasilitas')->findOrFail($id);

        // Return data dalam bentuk JSON
        return response()->json([
            'nama_ruangan' => $ruangan->nama_ruangan,
            'kapasitas' => $ruangan->kapasitas,
            'fasilitas' => $ruangan->fasilitas->map(function ($fasilitas) {
                return [
                    'nama_barang' => $fasilitas->nama_barang,
                    'kondisi' => $fasilitas->kondisi,
                ];
            }),
        ]);
    }
}
