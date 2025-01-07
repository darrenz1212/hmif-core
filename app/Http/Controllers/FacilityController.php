<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FacilityController
{
    public function store(Request $request, $roomId)
    {
        $validateData = $request->validate([
            'nama_barang' =>'required|string|max:255',
            'kondisi_barang'=>'required|string|max:255',
        ]);

        $validateData['id_ruangan'] = $roomId;
        Fasilitas::create($validateData);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kondisi_barang' => 'required|string|max:255',
        ]);

        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return response()->json([
                'message' => 'Fasilitas tidak ditemukan.'
            ], 404);
        }

        // Update data fasilitas
        $fasilitas->update($validatedData);

        return response()->json([
            'message' => 'Fasilitas berhasil diperbarui.',
            'data' => $fasilitas
        ], 200);
    }
}
