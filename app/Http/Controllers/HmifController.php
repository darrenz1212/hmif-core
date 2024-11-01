<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HmifController extends Controller
{
    public function index()
    {
        return view('hmif.dashboard');
    }
}
