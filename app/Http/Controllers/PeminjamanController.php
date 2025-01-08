<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{



//    Fetch all peminjaman status in DB
//    Used in :
//    HmifController statusPemRuangan()
    public function getStatusPeminjaman()
    {
        $peminjamanRuangan = DB::table('peminjaman_ruangan')
            ->select(
                'peminjaman_ruangan.id_peminjaman_ruangan',
                'peminjaman_ruangan.surat_peminjaman',
                'peminjaman_ruangan.keterangan_peminjaman',
                'peminjaman_ruangan.tanggal_peminjaman',
                'peminjaman_ruangan.jam_mulai',
                'peminjaman_ruangan.jam_selesai',
                'peminjaman_ruangan.status',
                'users.name as nama_peminjam',
                'ruangan.nama_ruangan'
            )
            ->join('users', 'peminjaman_ruangan.id_peminjam', '=', 'users.id')
            ->join('ruangan', 'peminjaman_ruangan.id_ruangan', '=', 'ruangan.room_id')
            ->get();

        return $peminjamanRuangan;
    }

        public function approved(Request $request, $id)
    {
        // Validasi input feedback
        $validated = $request->validate([

            'feedback' => 'required|string|max:255',
        ]);

        try {
            // Cari data peminjaman berdasarkan ID
            $peminjamanRuangan = PeminjamanRuangan::findOrFail($id);

            $peminjaman = $peminjamanRuangan;
            // Update status menjadi disetujui
            $peminjaman->update([
                'status' => 'disetujui',
                'feedback' => $validated['feedback'],
            ]);

            // Panggil fungsi addJadwal dari JadwalRuanganController
            $jadwalRuanganController = app(JadwalRuanganController::class);
            $jadwalRuanganController->addJadwal(new Request([
                'room_id' => $peminjaman->id_ruangan,
                'tanggal' => $peminjaman->tanggal_peminjaman,
                'jam_mulai' => $peminjaman->jam_mulai,
                'jam_selesai' => $peminjaman->jam_selesai,
                'keterangan' => $peminjaman->keterangan_peminjaman,
                'isRepeat' => '0',
            ]));

            return redirect()->back()->with('success', 'Peminjaman telah disetujui dan jadwal ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }




}
