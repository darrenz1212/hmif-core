<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'nama_barang',
        'kondisi',
    ];

    /**
     * Relasi ke model PeminjamanInventaris (many-to-many jika inventaris dapat dipinjam beberapa kali)
     */
    public function peminjamanInventaris()
    {
        return $this->belongsToMany(PeminjamanInventaris::class, 'inventaris_peminjaman', 'id_inventaris', 'id_peminjaman_inventaris');
    }
}
