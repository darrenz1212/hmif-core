<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_ruangan';

    protected $primaryKey = 'id_peminjaman_ruangan';

    public $timestamps = false;

    protected $fillable = [
        'id_ruangan',
        'id_peminjam',
        'surat_peminjaman',
        'keterangan_peminjaman',
        'tanggal_peminjaman',
        'jam_mulai',
        'jam_selesai',
        'status',
        'feedback'
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
}
