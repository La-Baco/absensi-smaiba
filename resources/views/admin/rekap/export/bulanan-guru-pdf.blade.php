<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .hadir {
            background-color: #d4edda;
        }

        .telat {
            background-color: #fff3cd;
        }

        .izin {
            background-color: #cce5ff;
        }

        .sakit {
            background-color: #f8d7da;
        }

        .alpha {
            background-color: #f5c6cb;
        }

        .libur {
            background-color: #e2e3e5;
        }

        .kosong {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <h3 style="margin-bottom: 5px;">Rekap Absensi Bulanan Guru</h3>
    <p style="margin-top: 0;">Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 140px; text-align: left; padding-left: 6px;">Nama Guru</th>
                @foreach ($rangeTanggal as $tanggal)
                    <th style="width: 15px;">{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</th>
                @endforeach
                <th style="width: 20px;">H</th>
                <th style="width: 20px;">T</th>
                <th style="width: 20px;">I</th>
                <th style="width: 20px;">S</th>
                <th style="width: 20px;">A</th>
                <th style="width: 25px;">Total Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guru as $g)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td style="text-align: left; padding-left: 6px;">{{ $g['guru']->nama }}</td>
        @php
            $total = ['Hadir' => 0, 'Telat' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
            $firstDate = $g['first'];
            $lastDate = $g['last'];
        @endphp

        @foreach ($rangeTanggal as $tanggal)
            @php
                $raw = $g['absen'][$tanggal] ?? null;

                if ($raw) {
                    $status = $raw['status'];
                    $kode = $raw['kode'];
                } elseif (in_array($tanggal, $hariLibur)) {
                    $status = 'libur';
                    $kode = 'L';
                } elseif (\Carbon\Carbon::parse($tanggal)->isSunday()) {
                    $status = 'minggu';
                    $kode = 'M';
                } elseif ($tanggal >= $firstDate && $tanggal <= $lastDate) {
                    $status = 'Alpha';
                    $kode = 'A';
                } else {
                    $status = '-';
                    $kode = '-';
                }

                $class = match (strtolower($status)) {
                    'hadir' => 'hadir',
                    'telat' => 'telat',
                    'izin' => 'izin',
                    'sakit' => 'sakit',
                    'alpha' => 'alpha',
                    'libur', 'minggu' => 'libur',
                    default => 'kosong',
                };

                if (isset($total[$status])) {
                    $total[$status]++;
                }
            @endphp

            <td class="{{ $class }}">{{ $kode }}</td>
        @endforeach

        <td><strong>{{ $total['Hadir'] }}</strong></td>
        <td>{{ $total['Telat'] }}</td>
        <td>{{ $total['Izin'] }}</td>
        <td>{{ $total['Sakit'] }}</td>
        <td>{{ $total['Alpha'] }}</td>
        <td><strong>{{ $total['Hadir'] + $total['Telat'] }}</strong></td>
    </tr>
@empty
    <tr>
        <td colspan="{{ count($rangeTanggal) + 8 }}">Tidak ada data</td>
    </tr>
@endforelse

        </tbody>
    </table>
</body>

</html>
