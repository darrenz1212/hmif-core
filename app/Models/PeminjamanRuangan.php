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
        'status',
    ];

    /**
     * Relasi ke model Ruangan (satu peminjaman memiliki satu ruangan)
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_peminjam','id_peminjam');
    }
}
