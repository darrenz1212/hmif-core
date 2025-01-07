<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FaciityController
{
    public function storeFacility(Request $request)
    {
        $validateData = $request->validate([
            'nama_barang' =>'required|string|max:255',
            'kondisi_barang'=>'required|string|max:255',
            'id_ruangan'=> 'required|integer|max:10',
        ]);
        Fasilitas::create($validateData);
    }
}
