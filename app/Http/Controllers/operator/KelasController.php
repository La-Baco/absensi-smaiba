<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;

class KelasController extends Controller
{
    // Tampilkan daftar semua kelas
    public function index()
    {
        $kelasList = Kelas::withCount('siswa')->get(); // include jumlah siswa
        return view('operator.kelas.index', compact('kelasList'));
    }

    // Tampilkan daftar siswa dalam kelas tertentu
    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        $siswaList = Siswa::where('id_kelas', $id)->where('aktif', 1)->get();

        return view('operator.kelas.siswa', compact('kelas', 'siswaList'));
    }
}
