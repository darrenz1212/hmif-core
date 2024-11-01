<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function showAllRoom()
    {
        $ruangan = Ruangan::all();

        return view('kalab.room-view', [
            'ruangan'=> $ruangan
        ]);
    }
}
