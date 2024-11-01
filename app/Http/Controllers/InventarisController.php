<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;

class InventarisController extends Controller
{
    public function index()
    {
        $inventaris = Inventaris::all();
        return view('inventaris.index', compact('inventaris'));
    }
}
