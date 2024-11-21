<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class KalabController extends Controller
{
    public function index()
    {
        return view('kalab.dashboard');
    }

    public function showAllRoom()
    {
        $getruangan = new RoomController();
        $ruangan = $getruangan->getAllRoom();
        return view('kalab.room-view', [
            'ruangan'=> $ruangan
        ]);

    }

    public function createRoom(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);
        $validatedData['ketersediaan'] = 1;
        Ruangan::create($validatedData);
        return redirect()->route('kalab-showroom')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function updateRoom(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'ketersediaan' => 'required|boolean',
        ]);
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($validatedData);

        return redirect()->route('kalab-showroom')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function deleteRoom($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('kalab-showroom')->with('success', 'Ruangan berhasil dihapus.');
    }

//    All inventory method is decomposition from inventoryController
    public function showInventaris()
    {
        $inventoryController = new InventoryController();

        $getInventaris = $inventoryController->getAllInventaris();

        return view('kalab.inventoryControl',[
            'inventaris' => $getInventaris
        ]);
    }

    public function createInventaris(Request $request)
    {
        $inventoryController = new InventoryController();
        $inventoryController ->store($request);

        return redirect()->route('inventory')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function updateInventaris(Request $request,$id)
    {
        $inventoryController = new InventoryController();
        $inventoryController->update($request,$id);

        return redirect()->route('inventory')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function deleteInventaris($id)
    {
        $inventoryController = new InventoryController();
        $inventoryController->destroy($id);

        return redirect()->route('inventory')->with('success', 'Inventaris berhasil dihapus.');
    }


}
