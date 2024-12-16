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
    }

    public function editRoomFacilities($id)
    {
        $room = Ruangan::with('fasilitas')->findOrFail($id);
        return view('stafflab.editFacilities', compact('room'));
    }

    public function updateRoomFacilities(Request $request, $id)
    {
        $room = Ruangan::findOrFail($id);

        // Validasi input
        $request->validate([
            'fasilitas' => 'required|array',
            'fasilitas.*' => 'string|max:255',
        ]);

        // Hapus fasilitas lama dan tambahkan fasilitas baru
        $room->fasilitas()->delete();
        foreach ($request->fasilitas as $namaBarang) {
            $room->fasilitas()->create(['nama_barang' => $namaBarang]);
        }

        return redirect()->route('stafflab.rooms')->with('success', 'Fasilitas ruangan berhasil diperbarui.');
    }
    
    public function showAllRoom()
    {
        $ruangan = Ruangan::All();
        return view('stafflab.rooms', compact('ruangan'));
    }

    // Menyimpan ruangan baru
    public function storeRooms(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
        ]);

        return redirect()->route('stafflab.rooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    // Mengupdate data ruangan
    public function updateRooms(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
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
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kondisi' => 'required|string|in:Baik,Rusak',
        ]);

        Inventaris::create($request->only(['nama_barang', 'kondisi']));
        return redirect()->route('stafflab.inventory')->with('success', 'Data inventaris berhasil ditambahkan!');
    }

    public function updateInventory(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|exists:inventaris,id_inventaris',
            'kondisi' => 'required|string|in:Baik,Rusak', // Hanya validasi untuk kondisi
        ]);

        // Cari inventaris berdasarkan ID
        $inventaris = Inventaris::findOrFail($request->id_inventaris);

        // Perbarui hanya kondisi
        $inventaris->update([
            'kondisi' => $request->kondisi,
        ]);

        return redirect()->route('stafflab.inventory')->with('success', 'Kondisi barang berhasil diperbarui!');
    }
}
