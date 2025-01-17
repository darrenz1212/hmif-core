<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use App\Models\Inventaris;

class StaffController extends Controller
{
    public function index()
    {
        return view('stafflab.dashboard');
    }

    public function showAllRoomFacilities()
    {
        $ruangan = Ruangan::with('fasilitas')->get();
        return view('stafflab.roomFacilities', compact('ruangan'));

//        dd($ruangan[0]->fasilitas[0]->nama_barang);
    }


    public function storeFacility(Request $request, $roomId)
    {
        $facilityController = new FacilityController();
        $facilityController->store($request, $roomId);

        return redirect()->route('stafflab.editFacilities', $roomId)->with('success', 'Fasilitas berhasil ditambahkan.');
    }
    public function editRoomFacilities($id)
    {
        $room = Ruangan::with('fasilitas')->findOrFail($id);
        return view('stafflab.editFacilities', compact('room'));
    }

    public function updateRoomFacilities(Request $request, $id)
    {
        $facilityController = new FacilityController();
        $facilityController->update($request, $id);

        $fasilitas = Fasilitas::findOrFail($id);
        $roomId = $fasilitas->id_ruangan;

        return redirect()->route('stafflab.editFacilities', $roomId)
            ->with('success', 'Fasilitas ruangan berhasil diperbarui.');
    }

    public function jadwalRuangan()
    {
        // Render halaman kalender
        return view('stafflab.jadwalRuangan');
    }

    public function showAllRoom()
    {
        $ruangan = Ruangan::All();
        return view('stafflab.rooms', compact('ruangan'));
    }

    // Menyimpan ruangan baru
    public function storeRooms(Request $request)
    {
        $roomController = new RoomController();
        $roomController ->storeRoom($request);

        return redirect()->route('stafflab.rooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    // Mengupdate data ruangan
    public function updateRooms(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'ketersediaan' => 'required|boolean'
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'ketersediaan' => $request->ketersediaan
        ]);

        return redirect()->route('stafflab.rooms')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function inventory()
    {
        $inventaris = Inventaris::all();
        return view('stafflab.inventory', compact('inventaris'));
    }

    public function storeInventory(Request $request)
    {
        $inventorController = new InventoryController();
        $inventorController->store($request);

        return redirect()->route('stafflab.inventory')->with('success', 'Data inventaris berhasil ditambahkan!');
    }
    public function updateInventory(Request $request, $id)
    {
        $inventorController = new InventoryController();
        $inventorController->update($request,$id);
        return redirect()->route('stafflab.inventory')->with('success', 'Kondisi barang berhasil diperbarui!');
    }


}
