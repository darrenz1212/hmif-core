<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Inventaris;
use App\Models\PeminjamanInventaris;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Auth;

class HmifController extends Controller
{
    public function index()
    {
        return view('hmif.dashboard');
    }

//    Fetching status peminjaman from getStatusPeminjaman in PeminjamanController
    public function statusPemRuangan()
    {
        $peminjaman = new PeminjamanController();
        $peminjamanRuangan = $peminjaman->getStatusPeminjaman();

        return view('hmif.statusPemRuangan', ['peminjamanRuangan' => $peminjamanRuangan]);
    }

//    Fetching jadwal Ruangan from getJadwalRuangan function in RoomController
    public function jadwalRuangan()
    {
        // Render halaman kalender
        return view('hmif.jadwalRuangan');
    }

    public function getJadwalRuangan()
    {
        // Ambil data jadwal dari RoomController
        $roomController = new RoomController();
        $jadwalRuangan = $roomController->getJadwalRuangan();

        // Kembalikan data dalam format JSON untuk FullCalendar
        return response()->json($jadwalRuangan);
    }

    public function getRuangan()
    {
        $getRuangan = new RoomController();
        $ruangan = $getRuangan ->getAllRoom();

        return response()->json($ruangan);
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



    public function pengajuanRuangan()
    {
        $ruanganList = Ruangan::all();

        $userList = User::all();

        return view('hmif.pengajuanRuangan', [
            'ruanganList' => $ruanganList,
            'userList' => $userList,
        ]);
    }

    public function submitPengajuanRuangan(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_ruangan' => 'required|integer|exists:ruangan,room_id',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Membentuk nama file sesuai format untuk ruangan
        $fileName = sprintf('ruangan_%d%d%d.pdf', DB::table('peminjaman_ruangan')->max('id_peminjaman_ruangan') + 1, Auth::id(), $validatedData['id_ruangan']);

        // Menyimpan file dengan nama sesuai format ke folder tertentu
        $filePath = $request->file('surat_peminjaman')->storeAs('surat_peminjaman', $fileName, 'public');

        // Insert data ke tabel dengan path file
        DB::table('peminjaman_ruangan')->insert([
            'id_ruangan' => $validatedData['id_ruangan'],
            'id_peminjam' => Auth::id(),
            'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'surat_peminjaman' => $filePath,
            'keterangan_peminjaman' => $request->keterangan_peminjaman ?? null,
            'status' => 'sedang diajukan',
        ]);

        return redirect()->route('statusPemRuangan')->with('success', 'Peminjaman ruangan berhasil ditambahkan.');
    }


    public function showAllRoomFacilities()
    {
        $ruangan = Ruangan::with('fasilitas')->get();
        return view('hmif.fasilitas', compact('ruangan'));
    }

    public function viewPeminjamanBarang()
    {
        $peminjaman = PeminjamanInventaris::all();
        $inventaris = Inventaris::all();
        return view('hmif.PemBarang', compact('peminjaman', 'inventaris'));
    }

    public function checkAvailability(Request $request)
    {
        $tanggal = $request->tanggal;
        $jamMulai = $request->jamMulai;
        $jamSelesai = $request->jamSelesai;

        // Cari barang yang sudah dipinjam pada tanggal dan waktu tersebut
        $peminjaman = PeminjamanInventaris::where('tanggal_peminjaman', $tanggal)
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$jamMulai])
                    ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$jamSelesai]);
            })
            ->pluck('id_inventaris');

        // Cari barang yang tidak dipinjam pada waktu tersebut
        $availableItems = Inventaris::whereNotIn('id_inventaris', $peminjaman)->get();

        return response()->json($availableItems);
    }

    public function checkRoomAvailability(Request $request)
    {
        $validated = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $ruanganTersedia = Ruangan::whereNotIn('room_id', function ($query) use ($validated) {
            $query->select('id_ruangan')
                ->from('peminjaman_ruangan')
                ->where('tanggal_peminjaman', $validated['tanggal_peminjaman'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']]);
                });
        })->get();

        return response()->json(['available' => $ruanganTersedia->isNotEmpty(), 'ruangan' => $ruanganTersedia]);
    }



    /**
     * Menyimpan data peminjaman baru dari modal.
     */
    public function storePeminjamanBarang(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:2048',
            'id_inventaris' => 'required|integer|exists:inventaris,id_inventaris',
            'keterangan_peminjaman' => 'required|string|max:255'
        ]);

        // Pastikan barang tidak dipinjam pada waktu tersebut
        $isAvailable = PeminjamanInventaris::where('tanggal_peminjaman', $validatedData['tanggal_peminjaman'])
            ->where('id_inventaris', $validatedData['id_inventaris'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$validatedData['jam_mulai']])
                    ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$validatedData['jam_selesai']]);
            })
            ->doesntExist();

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Barang tidak tersedia pada waktu tersebut.');
        }

        // Dapatkan id_peminjaman terbaru (autoincrement) dari database
        $idPeminjaman = PeminjamanInventaris::max('id_peminjaman_inventaris') + 1;

        // Membentuk nama file sesuai format untuk barang
        $fileName = sprintf('barang_%d%d%d.pdf', $idPeminjaman, Auth::id(), $validatedData['id_inventaris']);

        // Menyimpan file dengan nama sesuai format ke folder tertentu
        $filePath = $request->file('surat_peminjaman')->storeAs('surat_peminjaman', $fileName, 'public');

        // Simpan data peminjaman
        PeminjamanInventaris::create([
            'id_peminjam' => Auth::id(),
            'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'surat_peminjaman' => $filePath,
            'id_inventaris' => $validatedData['id_inventaris'],
            'status' => 'diajukan',
            'keterangan_peminjaman' => $validatedData['keterangan_peminjaman'],
        ]);

        return redirect()->route('hmif.PemBarang')->with('success', 'Peminjaman berhasil ditambahkan.');
    }
}
