<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
//    Fetch inventaris in DB
    /*
     *   used in :
     *   KalabController showInventaris()
     *   StafflabController showInventaris() *Not done yet*
     */
    public function getAllInventaris()
    {
        $inventaris = Inventaris::all();

        return $inventaris;
    }

//    Create Inventaris
    /*
     *   used in :
     *   KalabController createInventaris()
     *   StafflabController createInventaris() *Not done yet*
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
        ]);

        Inventaris::create($validatedData);
    }

//    Updating inventaris
    /*
     *   used in :
     *   KalabController updateInventaris()
     *   StafflabController updateInventaris() *Not done yet*
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
        ]);

        $inventaris = Inventaris::findOrFail($id);
        $inventaris->update($validatedData);
    }

//    Deleting inventaris
/*
 *   used in :
 *   KalabController deleteInventaris()
 *   StafflabController deleteInventaris() *Not done yet*
 */
    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();
    }
}
