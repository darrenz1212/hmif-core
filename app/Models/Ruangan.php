<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $primaryKey = 'room_id';

    public $timestamps = false;
    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'ketersediaan'
    ];

    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'id_ruangan', 'room_id');
    }

    public function peminjamanRuangan()
    {
        return $this->belongsToMany(PeminjamanRuangan::class, 'ruangan_peminjaman', 'ruangan_id', 'peminjaman_id');
    }
}
