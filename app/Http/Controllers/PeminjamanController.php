<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            $peminjaman = PeminjamanRuangan::findOrFail($id);

            $jamMulai = Carbon::parse($peminjaman->jam_mulai)->format('H:i');
            $jamSelesai = Carbon::parse($peminjaman->jam_selesai)->format('H:i');

            // Update status menjadi disetujui
            $peminjaman->update([
                'status' => 'disetujui',
                'feedback' => $validated['feedback'],
            ]);

            $jadwalRuanganController = app(JadwalRuanganController::class);
            $jadwalRuanganController->addJadwal(new Request([
                'room_id' => $peminjaman->id_ruangan,
                'tanggal' => $peminjaman->tanggal_peminjaman,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'keterangan' => $peminjaman->keterangan_peminjaman,
                'isRepeat' => '0',
            ]));


        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function decline(Request $request, $id)
    {
        $validated = $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        try {
            $peminjaman = PeminjamanRuangan::findOrFail($id);
            $peminjaman->update([
                'status' => 'ditolak',
                'feedback' => $validated['feedback'],
            ]);

            return redirect()->back()->with('success', 'Peminjaman telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }





}
