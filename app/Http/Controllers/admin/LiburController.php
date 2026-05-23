<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Libur;
use Illuminate\Http\Request;

class LiburController extends Controller
{
    public function index()
    {
        $libur = Libur::all();
        return view('admin.libur.index', compact('libur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_libur' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Libur::create([
            'nama_libur' => $request->nama_libur,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.libur.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }


    public function update(Request $request, Libur $libur)
    {
        $request->validate([
            'nama_libur' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $libur->update([
            'nama_libur' => $request->nama_libur,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Hari libur berhasil diperbarui');
    }


    public function destroy(Libur $libur)
    {
        $libur->delete();
        return redirect()->back()->with('success', 'Hari libur berhasil dihapus');
    }
}
