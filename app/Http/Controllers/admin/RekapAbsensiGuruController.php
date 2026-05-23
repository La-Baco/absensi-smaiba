<?php

namespace App\Http\Controllers\admin;

use App\Models\Guru;
use App\Models\Libur;
use App\Models\Absensi;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapGuruBulananExport;

class RekapAbsensiGuruController extends Controller
{
    public function harianGuru(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $data = Absensi::with('guru')
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('id_guru')
            ->orderBy('id_guru')
            ->get();

        return view('admin.rekap.harian_guru', compact('data', 'tanggal'));
    }

    public function bulananGuru(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

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

        $guruList = Guru::with(['absensi' => function ($query) use ($bulan) {
            $query->whereBetween('tanggal', [
                Carbon::parse($bulan . '-01'),
                Carbon::parse($bulan . '-01')->endOfMonth()
            ]);
        }])->get();

        $rekap = [];

        foreach ($guruList as $guru) {
            $absensiMap = $guru->absensi->keyBy(function ($absen) {
                return Carbon::parse($absen->tanggal)->format('Y-m-d');
            });

            $firstDate = optional($guru->absensi->min('tanggal')) ? Carbon::parse($guru->absensi->min('tanggal')) : null;
            $lastDate = optional($guru->absensi->max('tanggal')) ? Carbon::parse($guru->absensi->max('tanggal')) : null;

            $data = [];

            foreach ($tanggalList as $tgl) {
                $status = optional($absensiMap->get($tgl))->status;
                $isMinggu = Carbon::parse($tgl)->isSunday();
                $isLibur = in_array($tgl, $tanggalLibur);

                $kode = '-';
                $bg = 'bg-light';
                $clickable = true;

                if ($isLibur) {
                    $kode = 'L';
                    $bg = 'bg-secondary text-white';
                    $status = 'Libur';
                } elseif ($isMinggu) {
                    $kode = 'M';
                    $bg = 'bg-secondary text-white';
                    $status = 'Minggu';
                } elseif (!$status) {
                    // Jika guru tidak memiliki catatan absensi apapun pada tanggal ini, tampilkan '-'
                    if (!$absensiMap->has($tgl)) {
                        $kode = '-';
                        $bg = 'bg-light';
                        $status = '-';
                        $clickable = true;
                    } else {
                        $kode = 'A';
                        $bg = 'bg-danger text-white';
                        $status = 'Alpha';
                        $clickable = true;
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
                'guru' => $guru,
                'absen' => $data,
            ];
        }

        return view('admin.rekap.bulanan_guru', compact('bulan', 'tanggalList', 'rekap'));
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Alpha,Telat,Izin,Sakit'
        ]);

        $absen = Absensi::firstOrNew([
            'id_guru' => $request->id_guru,
            'tanggal' => $request->tanggal,
        ]);

        $absen->status = $request->status;
        $absen->save();

        return response()->json(['success' => true]);
    }


    public function exportBulanan(Request $request, $format)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $carbonBulan = Carbon::createFromFormat('Y-m', $bulan);
        $jumlahHari = $carbonBulan->daysInMonth;

        $tanggalList = collect(range(1, $jumlahHari))->map(function ($day) use ($bulan) {
            return Carbon::createFromFormat('Y-m-d', $bulan . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))->format('Y-m-d');
        });

        // Ambil hari libur
        $startOfMonth = $carbonBulan->copy()->startOfMonth();
        $endOfMonth = $carbonBulan->copy()->endOfMonth();

        $liburList = Libur::where(function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
                ->orWhereBetween('tanggal_selesai', [$startOfMonth, $endOfMonth])
                ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('tanggal_mulai', '<=', $startOfMonth)
                        ->where('tanggal_selesai', '>=', $endOfMonth);
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

        // Guru + absensi
        $guruList = Guru::with(['absensi' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
        }])->where('aktif', 1)->get();

        $rekap = [];

        foreach ($guruList as $guru) {
            $absensiMap = $guru->absensi->keyBy(function ($absen) {
                return Carbon::parse($absen->tanggal)->format('Y-m-d');
            });

            $firstDate = optional($guru->absensi->min('tanggal')) ? Carbon::parse($guru->absensi->min('tanggal'))->format('Y-m-d') : null;
            $lastDate = optional($guru->absensi->max('tanggal')) ? Carbon::parse($guru->absensi->max('tanggal'))->format('Y-m-d') : null;

            $data = [];

            foreach ($tanggalList as $tgl) {
                $status = optional($absensiMap->get($tgl))->status;
                $isMinggu = Carbon::parse($tgl)->isSunday();
                $isLibur = in_array($tgl, $tanggalLibur);

                $kode = '-';

                if ($isLibur) {
                    $kode = 'L';
                    $status = 'Libur';
                } elseif ($isMinggu) {
                    $kode = 'M';
                    $status = 'Minggu';
                } elseif (!$status) {
                    $kode = '-';
                    $status = '-';
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
                'guru' => $guru,
                'first' => $firstDate,
                'last' => $lastDate,
                'absen' => $data,
            ];
        }

        $exportData = [
            'tanggalList' => $tanggalList,
            'rangeTanggal' => $tanggalList,
            'rekap' => $rekap,
            'bulan' => $bulan,
            'tahun' => $carbonBulan->year,
            'hariLibur' => $tanggalLibur,
            'guru' => $rekap, 
        ];

        if ($format === 'pdf') {
            $pdf = PDF::loadView('admin.rekap.export.bulanan-guru-pdf', $exportData)
                ->setPaper('legal', 'landscape');

            return $pdf->download('rekap-bulanan-guru.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new RekapGuruBulananExport($exportData), 'rekap-bulanan-guru.xlsx');
        }

        return redirect()->back()->with('error', 'Format export tidak dikenali.');
    }
}
