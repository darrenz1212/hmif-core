<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_ruangan';

    protected $fillable = [
        'id_ruangan',
        'id_peminjam',
        'surat_peminjaman',
        'keterangan_peminjaman',
        'tanggal_peminjaman',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    /**
     * Relasi ke model Ruangan (satu peminjaman memiliki satu ruangan)
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'room_id');
    }

    /**
     * Relasi ke model User (satu peminjaman memiliki satu peminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_peminjam', 'id');
    }

    /**
     * Scope untuk memeriksa apakah ruangan sedang digunakan dalam rentang waktu tertentu
     */
    public function scopeIsBooked($query, $tanggal_peminjaman, $jam_mulai, $jam_selesai)
    {
        return $query->where(function ($q) use ($tanggal_peminjaman, $jam_mulai, $jam_selesai) {
            $q->whereDate('tanggal_peminjaman', $tanggal_peminjaman)
              ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                  $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                        ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                        ->orWhere(function ($query) use ($jam_mulai, $jam_selesai) {
                            $query->where('jam_mulai', '<=', $jam_mulai)
                                  ->where('jam_selesai', '>=', $jam_selesai);
                        });
              });
        });
    }
}