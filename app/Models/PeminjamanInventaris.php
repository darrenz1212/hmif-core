<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanInventaris extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_inventaris';

    protected $primaryKey ='id_peminjaman_inventaris';

    protected $fillable = [
        'id_peminjam',
        'surat_peminjaman',
        'id_inventaris',
        'status',
        'tanggal_peminjaman',
        'jam_mulai',
        'jam_selesai',
        'keterangan_peminjaman',
    ];

    public $timestamps = false;

    /**
     * Relasi ke model Inventaris (banyak inventaris bisa dipinjam dalam satu peminjaman)
     */
    public function inventaris()
    {
        return $this->belongsToMany(Inventaris::class, 'peminjaman_inventaris', 'id_inventaris', 'id_inventaris');
    }
}
