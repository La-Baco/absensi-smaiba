<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    // Menampilkan semua siswa
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = \App\Models\Kelas::all();
        return view('admin.siswa.edit', compact('kelas','siswa'));
    }

    public function show($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }


    // Menyimpan data siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'uid' => 'required|unique:siswa,uid',
            'id_kelas' => 'required|exists:kelas,id',
            'aktif' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_siswa', 'public');
        }

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    // Simpan perubahan data siswa
    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $id,
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|exists:kelas,id',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'aktif' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $siswa = Siswa::findOrFail($id);

        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->jenis_kelamin = $request->jenis_kelamin;
        $siswa->id_kelas = $request->id_kelas;
        $siswa->tempat_lahir = $request->tempat_lahir;
        $siswa->tanggal_lahir = $request->tanggal_lahir;
        $siswa->alamat = $request->alamat;
        $siswa->aktif = $request->aktif;

        // handle upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if (!empty($siswa->foto) && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Upload foto baru ke direktori storage/app/public/foto_siswa
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');

            // Simpan path-nya ke kolom foto
            $siswa->foto = $fotoPath;
        }



        $siswa->save();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }


    // Menghapus siswa
    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto && Storage::exists('public/' . $siswa->foto)) {
            Storage::delete('public/' . $siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
