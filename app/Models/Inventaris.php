<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    public function peminjamanInventaris()
    {
        return $this->hasMany(PeminjamanInventaris::class, 'id_inventaris');
    }
}
