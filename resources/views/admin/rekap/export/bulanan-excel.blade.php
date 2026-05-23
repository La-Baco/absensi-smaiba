<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h3 style="margin-bottom: 5px;">Rekap Absensi Bulanan</h3>
    <p style="margin-top: 0;">Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="text-align: left; padding-left: 5px;">Nama Siswa</th>
                @foreach ($tanggalList as $tanggal)
                    <th>{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</th>
                @endforeach
                <th>H</th>
                <th>T</th>
                <th>I</th>
                <th>S</th>
                <th>A</th>
                <th>total Kehadiran</th> 
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align: left; padding-left: 5px;">{{ $r['siswa']->nama }}</td>

                    @php
                        $total = ['Hadir' => 0, 'Telat' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
                    @endphp

                    @foreach ($tanggalList as $tanggal)
                        @php
                            $absen = $r['absen'][$tanggal] ?? ['status' => '-', 'kode' => '-'];
                            $status = $absen['status'];
                            $kode = $absen['kode'];
                            if (isset($total[$status])) {
                                $total[$status]++;
                            }
                        @endphp
                        <td>
                            {{ $status === 'Hadir' ? '✓' : $kode }}
                        </td>
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
                    <td colspan="{{ count($tanggalList) + 8 }}">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
