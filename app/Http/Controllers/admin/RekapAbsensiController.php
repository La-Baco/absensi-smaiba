<?php

namespace App\Http\Controllers\admin;

use App\Models\Kelas;
use App\Models\Libur;
use App\Models\Siswa;
use App\Models\Absensi;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapBulananExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class RekapAbsensiController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $data = Absensi::with('siswa.kelas')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('id_siswa')
            ->get();

        return view('admin.rekap.harian', compact('data', 'tanggal'));
    }

    public function mingguan(Request $request)
    {
        // Ambil minggu dari input, atau default ke minggu ini
        $minggu = $request->input('minggu') ?? now()->format('Y-\WW');

        // Ubah input `YYYY-Wxx` jadi tanggal awal dan akhir minggu
        try {
            $parsed = Carbon::createFromFormat('Y-\WW', $minggu);
        } catch (\Exception $e) {
            $parsed = now();
        }

        $start = $parsed->startOfWeek(Carbon::MONDAY)->startOfDay();
        $end = $parsed->copy()->endOfWeek(Carbon::SATURDAY)->endOfDay();

        // Ambil semua siswa
        $siswa = Siswa::with('kelas')->get();

        // Ambil data absensi untuk periode ini
        $absensi = Absensi::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->with('siswa.kelas')
            ->get();

        // Buat rekap per siswa
        $rekap = [];

        foreach ($siswa as $s) {
            $data = $absensi->where('id_siswa', $s->id);

            $rekap[] = [
                'siswa' => $s,
                'hadir' => $data->where('status', 'Hadir')->count(),
                'telat' => $data->where('status', 'Telat')->count(),
                'izin' => $data->where('status', 'Izin')->count(),
                'sakit' => $data->where('status', 'Sakit')->count(),
                'alpha' => $data->where('status', 'Alpha')->count(),
            ];
        }

        return view('admin.rekap.mingguan', compact('rekap', 'start', 'end'));
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $kelasId = $request->get('kelas');

        $tanggalList = collect(range(1, Carbon::createFromFormat('Y-m', $bulan)->daysInMonth))
            ->map(function ($day) use ($bulan) {
                return Carbon::createFromFormat('Y-m-d', $bulan . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))->format('Y-m-d');
            });

        // Ambil tanggal-tanggal libur
        $liburList = Libur::where(function ($query) use ($bulan) {
            $start = Carbon::parse($bulan . '-01');
            $end = $start->copy()->endOfMonth();

            $query->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q) use ($start, $end) {
                    $q->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
        })->get();

        $tanggalLibur = [];
        foreach ($liburList as $libur) {
            $mulai = Carbon::parse($libur->tanggal_mulai);
            $selesai = Carbon::parse($libur->tanggal_selesai);
            while ($mulai->lte($selesai)) {
                $tanggalLibur[] = $mulai->format('Y-m-d');
                $mulai->addDay();
            }
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $siswaQuery = Siswa::with(['absensi' => function ($query) use ($bulan) {
            $query->whereBetween('tanggal', [
                Carbon::parse($bulan . '-01'),
                Carbon::parse($bulan . '-01')->endOfMonth()
            ]);
        }])->where('aktif', 1);

        if ($kelasId) {
            $siswaQuery->where('id_kelas', $kelasId);
        }

        $siswaList = $siswaQuery->get();

        $rekap = [];

        foreach ($siswaList as $siswa) {
            $absensiMap = $siswa->absensi->keyBy(function ($absen) {
                return Carbon::parse($absen->tanggal)->format('Y-m-d');
            });

            $firstDate = optional($siswa->absensi->min('tanggal')) ? Carbon::parse($siswa->absensi->min('tanggal'))->format('Y-m-d') : null;
            $lastDate = optional($siswa->absensi->max('tanggal')) ? Carbon::parse($siswa->absensi->max('tanggal'))->format('Y-m-d') : null;

            $data = [];

            foreach ($tanggalList as $tgl) {
                $status = optional($absensiMap->get($tgl))->status;
                $isMinggu = Carbon::parse($tgl)->isSunday();
                $isLibur = in_array($tgl, $tanggalLibur);

                $kode = '-';
                $bg = 'bg-light';
                $clickable = false;

                if ($isLibur) {
                    $kode = 'L';
                    $bg = 'bg-secondary text-white';
                    $status = 'Libur';
                } elseif ($isMinggu) {
                    $kode = 'M';
                    $bg = 'bg-secondary text-white';
                    $status = 'Minggu';
                } elseif (!$status) {
                    if ($firstDate && $lastDate && $tgl >= $firstDate && $tgl <= $lastDate) {
                        $kode = 'A';
                        $bg = 'bg-danger text-white';
                        $status = 'Alpha';
                        $clickable = true;
                    } else {
                        $kode = '-';
                        $bg = 'bg-light';
                        $status = '-';
                        $clickable = false;
                    }
                } else {
                    $map = [
                        'Hadir' => ['H', 'bg-success text-white'],
                        'Telat' => ['T', 'bg-warning text-dark'],
                        'Sakit' => ['S', 'bg-primary text-white'],
                        'Izin' => ['I', 'bg-primary text-white'],
                        'Alpha' => ['A', 'bg-danger text-white'],
                    ];
                    [$kode, $bg] = $map[$status] ?? ['-', 'bg-light'];
                    $clickable = true;
                }

                $data[$tgl] = [
                    'kode' => $kode,
                    'warna' => $bg,
                    'status' => $status,
                    'clickable' => $clickable
                ];
            }

            $rekap[] = [
                'siswa' => $siswa,
                'absen' => $data,
            ];
        }

        return view('admin.rekap.bulanan', compact(
            'bulan',
            'kelasId',
            'kelasList',
            'tanggalList',
            'rekap'
        ));
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Alpha,Telat,Izin,Sakit'
        ]);

        $absen = Absensi::firstOrNew([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $request->tanggal,
        ]);

        $absen->status = $request->status;
        $absen->save();

        return response()->json(['success' => true]);
    }




    public function exportBulanan(Request $request, $format)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $kelasId = $request->get('kelas');

        $tanggalList = collect(range(1, Carbon::createFromFormat('Y-m', $bulan)->daysInMonth))
            ->map(function ($day) use ($bulan) {
                return Carbon::createFromFormat('Y-m-d', $bulan . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))->format('Y-m-d');
            });

        // Ambil libur
        $liburList = Libur::where(function ($query) use ($bulan) {
            $start = Carbon::parse($bulan . '-01');
            $end = $start->copy()->endOfMonth();

            $query->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q) use ($start, $end) {
                    $q->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
        })->get();

        $tanggalLibur = [];
        foreach ($liburList as $libur) {
            $mulai = Carbon::parse($libur->tanggal_mulai);
            $selesai = Carbon::parse($libur->tanggal_selesai);
            while ($mulai->lte($selesai)) {
                $tanggalLibur[] = $mulai->format('Y-m-d');
                $mulai->addDay();
            }
        }

        // Ambil siswa
        $siswaQuery = Siswa::with(['absensi' => function ($query) use ($bulan) {
            $query->whereBetween('tanggal', [
                Carbon::parse($bulan . '-01'),
                Carbon::parse($bulan . '-01')->endOfMonth()
            ]);
        }])->where('aktif', 1);

        if ($kelasId) {
            $siswaQuery->where('id_kelas', $kelasId);
        }

        $siswaList = $siswaQuery->get();

        // Buat rekap
        $rekap = [];

        foreach ($siswaList as $siswa) {
            $absensiMap = $siswa->absensi->keyBy(function ($absen) {
                return Carbon::parse($absen->tanggal)->format('Y-m-d');
            });

            $firstDate = optional($siswa->absensi->min('tanggal')) ? Carbon::parse($siswa->absensi->min('tanggal'))->format('Y-m-d') : null;
            $lastDate = optional($siswa->absensi->max('tanggal')) ? Carbon::parse($siswa->absensi->max('tanggal'))->format('Y-m-d') : null;

            $data = [];

            foreach ($tanggalList as $tgl) {
                $status = optional($absensiMap->get($tgl))->status;
                $isMinggu = Carbon::parse($tgl)->isSunday();
                $isLibur = in_array($tgl, $tanggalLibur);

                $kode = '-';
                $bg = 'bg-light';
                $clickable = false;

                if ($isLibur) {
                    $kode = 'L';
                    $status = 'Libur';
                } elseif ($isMinggu) {
                    $kode = 'M';
                    $status = 'Minggu';
                } elseif (!$status) {
                    if ($firstDate && $lastDate && $tgl >= $firstDate && $tgl <= $lastDate) {
                        $kode = 'A';
                        $status = 'Alpha';
                    } else {
                        $kode = '-';
                        $status = '-';
                    }
                } else {
                    $map = [
                        'Hadir' => 'H',
                        'Telat' => 'T',
                        'Izin' => 'I',
                        'Sakit' => 'S',
                        'Alpha' => 'A',
                    ];
                    $kode = $map[$status] ?? '-';
                }

                $data[$tgl] = [
                    'kode' => $kode,
                    'status' => $status,
                ];
            }

            $rekap[] = [
                'siswa' => $siswa,
                'absen' => $data,
            ];
        }

        $exportData = [
            'tanggalList' => $tanggalList,
            'rekap' => $rekap,
            'bulan' => $bulan,
        ];

        if ($format === 'pdf') {
            $pdf = PDF::loadView('admin.rekap.export.bulanan-pdf', $exportData)
                ->setPaper('legal', 'landscape');

            return $pdf->download('rekap-bulanan.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new RekapBulananExport($exportData), 'rekap-bulanan.xlsx');
        }

        return redirect()->back()->with('error', 'Format export tidak dikenali.');
    }
}
