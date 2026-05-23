<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaktuAbsensi;

class WaktuAbsensiController extends Controller
{
    public function index()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Ambil data dan ubah jadi koleksi per hari (key: hari)
        $waktu = WaktuAbsensi::all()->keyBy('hari');

        return view('admin.WaktuAbsensi.index', compact('waktu', 'hariList'));
    }

    public function updateAll(Request $request)
    {
        $data = $request->input('data', []);

        foreach ($data as $hari => $waktu) {
            if (
                isset($waktu['jam_masuk_mulai'], $waktu['jam_masuk_akhir'], $waktu['jam_pulang_mulai'], $waktu['jam_pulang_akhir']) &&
                $waktu['jam_masuk_mulai'] && $waktu['jam_masuk_akhir'] && $waktu['jam_pulang_mulai'] && $waktu['jam_pulang_akhir']
            ) {
                WaktuAbsensi::updateOrCreate(
                    ['hari' => $hari],
                    [
                        'jam_masuk_mulai' => $waktu['jam_masuk_mulai'],
                        'jam_masuk_akhir' => $waktu['jam_masuk_akhir'],
                        'jam_pulang_mulai' => $waktu['jam_pulang_mulai'],
                        'jam_pulang_akhir' => $waktu['jam_pulang_akhir'],
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan waktu berhasil disimpan.');
    }

    public function reset()
    {
        WaktuAbsensi::truncate(); // Hapus semua data
        return redirect()->back()->with('success', 'Semua pengaturan waktu telah direset.');
    }
}
