<?php

namespace App\Http\Controllers;

use App\Models\JadwalRuangan;
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

    public function jadwalRuangan()
    {
        // Render halaman kalender
        return view('kalab.jadwalRuangan');
    }

    public function getRuangan()
    {
        $getRuangan = new RoomController();
        $ruangan = $getRuangan ->getAllRoom();

        return response()->json($ruangan);
    }

    public function getJadwalRuangan()
    {
        // Ambil data jadwal dari RoomController
        $roomController = new RoomController();
        $jadwalRuangan = $roomController->getJadwalRuangan();

        // Kembalikan data dalam format JSON untuk FullCalendar
        return response()->json($jadwalRuangan);
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

    public function createJadwal(Request $request)
    {
        $jadwalRuanganController = new JadwalRuanganController();
        $jadwalRuanganController->addJadwal($request);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }


    public function createRoom(Request $request)
    {
        $roomController = new RoomController();
        $roomController->storeRoom($request);
        return redirect()->route('kalab-showroom')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function updateRoom(Request $request, $id)
    {
        $roomController = new RoomController();
        $roomController->updateRoom($request,$id);

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

    public function showPengajuan()
    {
        $peminjamanController = new PeminjamanController();
        $peminjamanRuangan = $peminjamanController->getStatusPeminjaman();

        return view('kalab.peminjaman',[
            'peminjamanRuangan' => $peminjamanRuangan
        ]);
    }

    public function aprrovePengajuan(Request $request, $id)
    {
        $peminjamanController = new PeminjamanController();
        $peminjamanController->approved($request, $id);

        return redirect()->back()->with('success', 'Peminjaman telah disetujui dan jadwal ditambahkan.');
    }


}
