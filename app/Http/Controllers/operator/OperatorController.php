<?php

namespace App\Http\Controllers\operator;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Libur;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OperatorController extends Controller
{

    public function index()
    {
        $today = Carbon::today();

        // Hitung total entitas
        $totalSiswa = Siswa::count();
        $totalGuru  = Guru::count();
        $totalKelas = Kelas::count();
        $totalUsers = User::count();

        // Hitung status absensi hari ini
        $hadirHariIni = Absensi::whereDate('tanggal', $today)->where('status', 'Hadir')->count();
        $izinHariIni  = Absensi::whereDate('tanggal', $today)->where('status', 'Izin')->count();
        $sakitHariIni = Absensi::whereDate('tanggal', $today)->where('status', 'Sakit')->count();
        $alphaHariIni = Absensi::whereDate('tanggal', $today)->where('status', 'Alpha')->count();
        $telatHariIni = Absensi::whereDate('tanggal', $today)->where('status', 'Terlambat')->count();

        // Range tanggal untuk 7 hari terakhir
        $tanggalMulai = $today->copy()->subDays(6);
        $tanggalSelesai = $today;

        // Ambil jumlah hadir per hari selama 7 hari terakhir
        $dataKehadiran = Absensi::selectRaw('DATE(tanggal) as tgl, COUNT(*) as jumlah')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->where('status', 'Hadir')
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        // Format untuk chart (label nama hari dan data jumlah hadir)
        $labels = [];
        $values = [];
        foreach ($tanggalMulai->daysUntil($tanggalSelesai->copy()->addDay()) as $date) {
            $labels[] = $date->isoFormat('dddd'); // nama hari
            $values[] = $dataKehadiran->firstWhere('tgl', $date->toDateString())->jumlah ?? 0;
        }

        // Persentase kehadiran hari ini
        $totalAbsensi = Absensi::whereDate('tanggal', $today)->count();
        $persentaseKehadiran = $totalAbsensi > 0
            ? round(($hadirHariIni / $totalAbsensi) * 100, 2)
            : 0;

        // Daftar absensi detail hari ini (untuk tabel)
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->get();

        // Daftar libur mendatang
        $liburMendatang = Libur::where('tanggal_mulai', '>=', $today)
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        $absensiTerbaru = Absensi::with('siswa') // pastikan relasi sudah ada di model Absensi
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $today = Carbon::today();
        $startDate = $today->copy()->subDays(6); // 7 hari terakhir

        $kelasJumlahSiswa = Kelas::select('nama_kelas', DB::raw('COUNT(siswa.id) as jumlah_siswa'))
            ->leftJoin('siswa', 'kelas.id', '=', 'siswa.id_kelas')
            ->groupBy('kelas.id', 'nama_kelas')
            ->get();

        return view('operator.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alphaHariIni',
            'telatHariIni',
            'persentaseKehadiran',
            'absensiHariIni',
            'liburMendatang',
            'totalKelas',
            'totalUsers',
            'labels',
            'values',
            'absensiTerbaru',
            'kelasJumlahSiswa'

        ));
    }

    public function editPassword()
    {
        return view('operator.password.index');
    }

    // Proses ubah password operator
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // pastikan pakai model User yang benar
        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('operator.password.edit')->with('success', 'Password berhasil diubah.');
    }
}
