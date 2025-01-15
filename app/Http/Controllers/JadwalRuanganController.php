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

    public function getJadwalById($id)
    {
        $jadwal = JadwalRuangan::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        return response()->json($jadwal);
    }

    public function editJadwal(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $hari = date('N', strtotime($request->tanggal));
        $jam_mulai = strtotime($request->jam_mulai);
        $jam_selesai = strtotime($request->jam_selesai);

        if ($hari > 6 || $hari < 1 || $jam_mulai < strtotime('07:00') || $jam_selesai > strtotime('21:00')) {
            return response()->json(['message' => 'Jadwal harus pada hari Senin-Sabtu antara pukul 07.00 - 21.00'], 422);
        }

        $jadwal = JadwalRuangan::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Update jadwal utama
        $jadwal->update($request->only(['tanggal', 'jam_mulai', 'jam_selesai', 'keterangan']));

        // Update jadwal 6 bulan ke depan jika diminta
        if ($request->isRepeat) {
            $relatedJadwals = JadwalRuangan::where('room_id', $jadwal->room_id)
                ->where('keterangan', $jadwal->keterangan)
                ->where('id', '>', $jadwal->id)
                ->get();

            $currentDate = strtotime($request->tanggal);
            $loopCount = 1;
            $conflictMessages = [];

            foreach ($relatedJadwals as $related) {
                // Tambahkan 7 hari pada setiap iterasi tanggal
                $currentDate = strtotime('+7 days', $currentDate);
                $formattedDate = date('Y-m-d', $currentDate);

                // Validasi bentrok jadwal
                $isConflict = JadwalRuangan::where('room_id', $related->room_id)
                    ->where('tanggal', $formattedDate)
                    ->where('keterangan', '!=', $request->keterangan)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                            ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
                    })
                    ->exists();

                if ($isConflict) {
                    // Jadwal bentrok ditemukan
                    $conflictMessages[] = "Jadwal bentrok pada tanggal $formattedDate dengan keterangan: {$related->keterangan}";
                    continue;
                }

                // Update jadwal yang tidak bentrok
                $related->update([
                    'tanggal' => $formattedDate,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'keterangan' => $request->keterangan,
                ]);

                $loopCount++;
            }

            // Jika ada pesan konflik, lempar response dengan error
            if (!empty($conflictMessages)) {
                return redirect()->route('klb.jadwalRuangan')
                    ->with('error', 'Terdapat jadwal yang bentrok:')
                    ->with('conflicts', $conflictMessages);
            }

        }
        return redirect()->route('klb.jadwalRuangan')->with('success', 'Jadwal berhasil diperbarui.');

    }

    public function getRelatedJadwal($id)
    {
        $jadwal = JadwalRuangan::find($id);

        if (!$jadwal) {
            return response()->json([], 404);
        }

        $relatedJadwals = JadwalRuangan::where('room_id', $jadwal->room_id)
            ->where('keterangan', $jadwal->keterangan)
            ->where('id', '>', $id)
            ->get();

        return response()->json($relatedJadwals);
    }

}
