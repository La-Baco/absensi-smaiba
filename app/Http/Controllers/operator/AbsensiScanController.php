<?php

namespace App\Http\Controllers\Operator;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Libur;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\WaktuAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AbsensiScanController extends Controller
{
    public function index()
    {
        $tanggal = Carbon::now()->format('Y-m-d');

        // Ambil semua absensi siswa hari ini (beserta relasi)
        $absenSiswa = Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('id_siswa')
            ->get();

        // Ambil semua absensi guru hari ini
        $absenGuru = Absensi::with('guru')
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('id_guru')
            ->get();

        return view('operator.absensi.index', compact('absenSiswa', 'absenGuru', 'tanggal'));
    }
    public function index2(){
        return view('operator.absensi.index2');
    }

    public function store(Request $request)
    {
        $uid = $request->input('uid');
        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');
        $hari = $now->translatedFormat('l');

        $hariMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIndonesia = $hariMapping[$hari] ?? $hari;

        // ❌ Cek Hari Minggu
        if ($hariIndonesia === 'Minggu') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari Minggu, tidak bisa absen.',
            ]);
        }

        // ❌ Cek Hari Libur
        $isLibur = Libur::whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->exists();

        if ($isLibur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini adalah hari libur.',
            ]);
        }

        // Ambil jadwal absensi
        $waktu = WaktuAbsensi::where('hari', $hariIndonesia)->first();
        if (!$waktu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal absensi belum diatur.',
            ]);
        }

        $jamMasukMulai  = Carbon::parse($waktu->jam_masuk_mulai);
        $jamMasukAkhir  = Carbon::parse($waktu->jam_masuk_akhir);
        $jamPulangMulai = Carbon::parse($waktu->jam_pulang_mulai);
        $jamPulangAkhir = Carbon::parse($waktu->jam_pulang_akhir);


        // ✅ Cek apakah UID milik guru
        $guru = Guru::where('uid', $uid)->first();
        if ($guru) {
            $absen = Absensi::firstOrNew([
                'id_guru' => $guru->id,
                'tanggal' => $tanggal,
            ]);

            // ✅ Absen Masuk
            if (!$absen->waktu_masuk) {
                if ($now->lt($jamMasukMulai)) {
                    return response()->json([
                        'status' => 'warning',
                        'tipe' => 'guru',
                        'message' => 'Belum waktunya absen masuk.',
                    ]);
                }

                $absen->waktu_masuk = $now;
                $absen->status = $now->gt($jamMasukAkhir) ? 'Telat' : 'Hadir';
                $absen->save();

                return response()->json([
                    'status' => 'success',
                    'tipe' => 'guru',
                    'message' => 'Absen masuk berhasil.',
                    'nama' => $guru->nama,
                    'uid' => $guru->uid,
                    'kelas' => '-',
                    'foto' => $guru->foto ? asset('storage/' . $guru->foto) : asset('assets/images/default.png'),
                    'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
                    'waktu_pulang' => $absen->waktu_pulang
                        ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                        : '-',
                    'absen_status' => $absen->status,
                ]);
            }

            // ✅ Absen Pulang
            if (!$absen->waktu_pulang) {
                if ($now->lt($jamPulangMulai)) {
                    return response()->json([
                        'status' => 'warning',
                        'tipe' => 'guru',
                        'message' => 'Belum waktunya absen pulang.',
                    ]);
                }

                if ($now->between($jamPulangMulai, $jamPulangAkhir)) {
                    $absen->waktu_pulang = $now;
                    $absen->save();

                    return response()->json([
                        'status' => 'success',
                        'tipe' => 'guru',
                        'message' => 'Absen pulang berhasil.',
                        'nama' => $guru->nama,
                        'uid' => $guru->uid,
                        'kelas' => '-',
                        'foto' => $guru->foto ? asset('storage/' . $guru->foto) : asset('assets/images/default.png'),
                        'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
                        'waktu_pulang' => $absen->waktu_pulang
                            ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                            : '-',
                        'absen_status' => 'Pulang',
                    ]);
                }
            }

            // ✅ Sudah absen masuk dan pulang
            return response()->json([
                'status' => 'info',
                'tipe' => 'guru',
                'message' => 'Sudah absen masuk dan pulang hari ini.',
                'nama' => $guru->nama,
                'uid' => $guru->uid,
                'kelas' => '-',
                'foto' => $guru->foto ? asset('storage/' . $guru->foto) : asset('assets/images/default.png'),
                'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
                'waktu_pulang' => $absen->waktu_pulang
                    ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                    : '-',
                'absen_status' => 'Selesai',
            ]);
        }


        // ✅ Cek apakah UID milik siswa
        $siswa = Siswa::with('kelas')->where('uid', $uid)->where('aktif', 1)->first();
        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'UID tidak dikenali atau siswa tidak aktif.',
            ]);
        }

        $absen = Absensi::firstOrNew([
            'id_siswa' => $siswa->id,
            'tanggal' => $tanggal,
        ]);

        if (!$absen->waktu_masuk && $now->gt($jamMasukAkhir)) {
            $absen->status = 'Alpha';
            $absen->save();
        }

        if (!$absen->waktu_masuk) {
            if ($now->between($jamMasukMulai, $jamPulangAkhir)) {
                $absen->waktu_masuk = $now;
                $absen->status = $now->gt($jamMasukAkhir) ? 'Telat' : 'Hadir';
                $absen->save();

                return response()->json([
                    'status' => 'success',
                    'tipe' => 'siswa',
                    'message' => 'Absen masuk berhasil.',
                    'nama' => $siswa->nama,
                    'uid' => $siswa->uid,
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                    'foto' => $siswa->foto ? asset('storage/' . $siswa->foto) : asset('assets/images/default.png'),
                    'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
                    'waktu_pulang' => $absen->waktu_pulang
                        ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                        : '-',
                    'absen_status' => $absen->status,
                ]);
            }

            return response()->json([
                'status' => 'warning',
                'message' => 'Belum waktunya absen masuk.',
            ]);
        }

        if (!$absen->waktu_pulang) {
            if ($absen->status === 'Alpha') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa absen pulang karena tidak absen masuk (Alpha).',
                ]);
            }

            if ($now->between($jamPulangMulai, $jamPulangAkhir)) {
                $absen->waktu_pulang = $now;
                $absen->save();

                return response()->json([
                    'status' => 'success',
                    'tipe' => 'siswa',
                    'message' => 'Absen pulang berhasil.',
                    'nama' => $siswa->nama,
                    'uid' => $siswa->uid,
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                    'foto' => $siswa->foto ? asset('storage/' . $siswa->foto) : asset('assets/images/default.png'),
                    'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
                    'waktu_pulang' => $absen->waktu_pulang
                        ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                        : '-',
                    'absen_status' => 'Pulang',
                ]);
            }

            return response()->json([
                'status' => 'warning',
                'message' => 'Belum waktunya absen pulang.',
            ]);
        }

        return response()->json([
            'status' => 'info',
            'tipe' => 'siswa',
            'message' => 'Sudah absen masuk dan pulang hari ini.',
            'nama' => $siswa->nama,
            'uid' => $siswa->uid,
            'kelas' => $siswa->kelas->nama_kelas ?? '-',
            'foto' => $siswa->foto ? asset('storage/' . $siswa->foto) : asset('assets/images/default.png'),
            'waktu_masuk' => \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i'),
            'waktu_pulang' => $absen->waktu_pulang
                ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                : '-',
            'absen_status' => 'Selesai',
        ]);
    }

    public function showAbsenSiswa(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $tanggal = Carbon::now()->toDateString(); // selalu hari ini

        // ambil daftar kelas
        $kelasList = Kelas::all();

        // ambil daftar siswa berdasarkan kelas (jika kelas dipilih)
        $siswaList = collect(); // default kosong
        if ($kelasId) {
            $siswaList = Siswa::with('kelas')
                ->where('id_kelas', $kelasId)
                ->get();
        }

        // ambil absensi siswa untuk hari ini
        $absensiHariIni = Absensi::whereDate('tanggal', $tanggal)
            ->whereIn('id_siswa', $siswaList->pluck('id'))
            ->get()
            ->keyBy('id_siswa'); // agar mudah dicari per siswa

        return view('operator.absensi.siswa', [
            'kelasList' => $kelasList,
            'kelasId' => $kelasId,
            'siswaList' => $siswaList,
            'absensiHariIni' => $absensiHariIni,
            'tanggal' => $tanggal,
        ]);
    }

    public function updateAbsenSiswa(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'status' => 'required|in:Hadir,Telat,Izin,Sakit,Alpha'
        ]);

        $tanggal = Carbon::now()->toDateString();

        // cari absensi siswa untuk hari ini
        $absen = Absensi::firstOrNew([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $tanggal,
        ]);

        $absen->status = $request->status;
        $absen->save();

        return back()->with('success', 'Status absensi siswa hari ini berhasil diperbarui.');
    }




    public function showAbsenGuru(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());

        // Ambil semua guru
        $guru = Guru::orderBy('nama')->get();

        // Ambil data absensi guru pada tanggal tersebut (kalau ada)
        $absensiGuru = Absensi::whereDate('tanggal', $tanggal)
            ->whereNotNull('id_guru')
            ->get()
            ->keyBy('id_guru'); // supaya mudah dicocokkan dengan id guru

        return view('operator.absensi.guru', [
            'guru' => $guru,
            'absensiGuru' => $absensiGuru,
            'tanggal' => $tanggal,
        ]);
    }

    public function updateAbsenGuru(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Telat,Izin,Sakit,Alpha'
        ]);

        // Simpan atau perbarui absensi
        $absen = Absensi::updateOrCreate(
            [
                'id_guru' => $request->id_guru,
                'tanggal' => $request->tanggal,
            ],
            [
                'status' => $request->status,
                'waktu_masuk' => $request->status === 'Hadir' ? now()->format('H:i:s') : null,
            ]
        );

        return back()->with('success', 'Status absensi guru berhasil disimpan atau diperbarui.');
    }
}
