<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari nama model (opsional jika tabel sudah dinamai 'ruangans' atau 'ruangan')
    protected $table = 'ruangan';

    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
    ];


    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'id_ruangan', 'room_id');
    }

    // Contoh relasi many-to-many
    public function peminjamanRuangan()
    {
        return $this->belongsToMany(PeminjamanRuangan::class, 'ruangan_peminjaman', 'ruangan_id', 'peminjaman_id');
    }
}
