<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanInventaris;

class PeminjamanController extends Controller
{
    public function approve()
    {
        $requests = PeminjamanInventaris::where('status', 'pending')->get();
        return view('peminjaman.approve', compact('requests'));
    }
    
}
