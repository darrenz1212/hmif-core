<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'nama_barang',
        'kondisi_barang',
        'id_ruangan',
    ];

    /**
     * Relasi ke model Ruangan (Fasilitas dimiliki oleh satu ruangan)
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'room_id');
    }
}
