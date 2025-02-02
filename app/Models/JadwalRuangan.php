<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRuangan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ruangan'; // Menyesuaikan nama tabel

    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
    ];



    /**
     * Relasi ke model Ruangan.
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
