<?php

namespace App\Http\Controllers\operator;

use App\Models\WaktuAbsensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaktuAbsensiController extends Controller
{
    public function index()
    {
        $waktuList = WaktuAbsensi::orderBy('hari')->get();
        return view('operator.waktu.index', compact('waktuList'));
    }
}
