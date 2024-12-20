<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalRuangan;

class RoomController extends Controller
{

    public function getAllRoom()
    {
        $ruangan = Ruangan::all();

        return $ruangan;
    }

public function getAvailableRooms(Request $request)
{
    if ($request->isMethod('get')) {
        // Handle GET request (e.g., show an empty form or page)
        return view('hmif.ketersediaanRuangan', [
            'availableRooms' => [],
            'requestData' => [],
        ]);
    }

    // Handle POST request (existing logic for processing the form)
    $request->validate([
        'tanggal_peminjaman' => 'required|date',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    ]);

    $allRooms = DB::table('ruangan')->get();

    $availableRooms = $allRooms->filter(function ($room) use ($request) {
        $isRoomBooked = JadwalRuangan::where('room_id', $room->room_id)
            ->where('tanggal', $request->tanggal_peminjaman)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($query) use ($request) {
                          $query->where('jam_mulai', '<=', $request->jam_mulai)
                                ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        return !$isRoomBooked;
    });

    return view('hmif.ketersediaanRuangan', [
        'availableRooms' => $availableRooms,
        'requestData' => $request->all(),
    ]);
}

    
    public function showRoomHima()
    {
        $ruangan = $this->getAllRoom();

        $availableRooms = $ruangan->filter(function ($room) {
            return $room->ketersediaan == 1;
        });

        return view('hmif.ketersediaanRuangan',[
            'availableRooms' => $availableRooms
        ]);
    }

    //    Not Tested yet
    public function showRoomStaff()
    {
        return view('staff.ketersediaanRuangan',[
            // 'ruangan' => $this->$ruangan
        ]);
    }

    public function showRoomKalab()
    {
        $ruangan = $this->getAllRoom();
        return view('kalab.room-view', [
            'ruangan'=> $ruangan
        ]);
    }


//  Fetch all jadwal ruangan data from DB
//  Used in :
//  HmifController jadwalRuangan()
   public function getJadwalRuangan()
    {
        $jadwalRuangan = DB::table('jadwal_ruangan')
            ->join('ruangan', 'jadwal_ruangan.room_id', '=', 'ruangan.room_id')
            ->select(
                'jadwal_ruangan.*',
                'ruangan.nama_ruangan'
            )
            ->get();

        return $jadwalRuangan;
    }

//    Finding room information
    public function getRoomInfo($id)
    {

        $ruangan = Ruangan::with('fasilitas')->findOrFail($id);


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
