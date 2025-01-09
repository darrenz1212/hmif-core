<?php

namespace App\Http\Controllers;

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
                'ruangan.nama_ruangan',
                'peminjaman_ruangan.feedback'
            )
            ->join('users', 'peminjaman_ruangan.id_peminjam', '=', 'users.id')
            ->join('ruangan', 'peminjaman_ruangan.id_ruangan', '=', 'ruangan.room_id')
            ->get();

        return $peminjamanRuangan;
    }

}
