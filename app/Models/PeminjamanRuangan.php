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
        'waktu_peminjaman',
        'batas_tanggal',
        'batas_waktu',
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
    public function scopeIsBooked($query, $tanggal_peminjaman, $waktu_peminjaman, $tanggal_batas, $waktu_batas)
    {
        return $query->where(function ($q) use ($tanggal_peminjaman, $waktu_peminjaman, $tanggal_batas, $waktu_batas) {
            $q->whereDate('tanggal_peminjaman', '<=', $tanggal_batas)
              ->whereDate('batas_tanggal', '>=', $tanggal_peminjaman)
              ->whereTime('waktu_peminjaman', '<=', $waktu_batas)
              ->whereTime('batas_waktu', '>=', $waktu_peminjaman);
        });
    }
}
