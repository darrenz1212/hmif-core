<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KalabController extends Controller
{
    public function index()
    {
        return view('kalab.dashboard');
    }
}
