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
//    public function index()
//    {
//        // Mengambil semua jadwal ruangan beserta data ruangan yang terkait
//        $jadwalRuangan = JadwalRuangan::with('ruangan')->get();
//
//        return view('hmif.jadwalRuangan', compact('jadwalRuangan'));
//    }

    public function index()
    {
        $jadwal = JadwalRuangan::with('ruangan')->get();

        // Konversi data ke format FullCalendar
        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->keterangan,
                'start' => $item->tanggal . 'T' . $item->jam_mulai,
                'end' => $item->tanggal . 'T' . $item->jam_selesai,
                'description' => $item->ruangan->nama ?? 'Unknown Room',
            ];
        });

        return response()->json($events);
    }

    public function addJadwal(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required|exists:ruangan,room_id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
            'isRepeat' => 'nullable|boolean',
        ]);

        $conflict = JadwalRuangan::where('room_id', $validatedData['room_id'])
            ->where('tanggal', $validatedData['tanggal'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhere(function ($q) use ($validatedData) {
                        $q->where('jam_mulai', '<=', $validatedData['jam_mulai'])
                            ->where('jam_selesai', '>=', $validatedData['jam_selesai']);
                    });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()->withErrors(['error' => 'Jadwal bertumpuk dengan jadwal yang sudah ada.']);
        }

        $isRepeat = filter_var($request->input('isRepeat'), FILTER_VALIDATE_BOOLEAN);
        $currentDate = $validatedData['tanggal'];

            // Loop untuk penjadwalan 6 bulan ke depan jika isRepeat true
        for ($i = 0; $i <= ($isRepeat ? 24 : 0); $i++) {
            JadwalRuangan::create([
                'room_id' => $validatedData['room_id'],
                'tanggal' => $currentDate,
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $validatedData['jam_selesai'],
                'keterangan' => $validatedData['keterangan'],
            ]);

            $currentDate = date('Y-m-d', strtotime($currentDate . ' +7 days'));
        }
//        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

}
