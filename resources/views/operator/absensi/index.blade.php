<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Siswa SMK Al-BUKHARI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('assets/images/logo/iconsmaiba1.png') }}" sizes="any">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            width: 30%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-right: 2px solid #e0e0e0;
            padding: 30px;
        }

        /* .left-panel img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            margin-bottom: 20px;
        } */

        .left-panel h2 {
            margin: 10px 0 5px;
            font-size: 22px;
        }

        .left-panel p {
            margin: 0;
            color: #666;
        }

        .right-panel {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            background-color: #0e4b79;
            color: #fff;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .clock {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .scan-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .scan-box input {
            font-size: 22px;
            padding: 14px;
            width: 60%;
            border: 2px solid #ccc;
            border-radius: 10px;
            text-align: center;
            outline: none;
        }

        .table-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            flex: 1;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #9ca3f7;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
        }

        table tbody tr:hover {
            background-color: #e2e6ea;
        }

        .scan-box input {
            transition: border 0.3s;
        }

        .scan-box input:focus {
            border-color: #0e4b79;
        }


        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr {
            transition: background-color 0.5s ease;
        }

        tr.status-Hadir {
            background-color: #d4edda;
        }

        tr.status-Telat {
            background-color: #fff3cd;
        }

        tr.status-Pulang {
            background-color: #cfe2ff;
        }


        .left-panel .foto-wrapper {
            width: 300px;
            /* 4 */
            height: 400px;
            /* 3 */
            border-radius: 8px;
            overflow: hidden;
            background-color: #f0f0f0;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .left-panel .foto-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div id="clock" class="clock ">--:--:--</div>

            <div class="foto-wrapper">
                <img id="fotoPengguna" src="{{ asset('assets/images/default.png') }}" alt="Foto">
            </div>
            <h2 id="namaPengguna">Nama</h2>
            <p id="uidPengguna">UID: -</p>
            <p id="tipePengguna" class="text-muted">-</p> <!-- Tipe: Siswa / Guru -->
        </div>



        <div class="right-panel">
            <div class="logo text-center" style="display: flex; flex-direction: column; align-items: center;">
                <img src="{{ asset('assets/images/logo/logo1.png') }}" height="150" alt="Logo Sekolah">
                <h2 class="mt-2 mb-0">SMA ISLAM BAITURRAHMAN</h2>
            </div>

            <div class="scan-box">
                <form id="absenForm" method="POST" action="{{ route('operator.absensi.store') }}">
                    @csrf
                    <input type="text" name="uid" id="uidInput" placeholder="Scan UID di sini..." autofocus>
                </form>
            </div>

            <div class="table-box">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>UID</th>
                            <th>Kelas</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="absenTabel">
                        @php $no = 1; @endphp

                        @foreach ($absenSiswa as $absen)
                            @php
                                $siswa = $absen->siswa;
                                $nama = $siswa->nama;
                                $tipe = 'Siswa';
                                $uid = $siswa->uid;
                                $kelas = $siswa->kelas->nama_kelas ?? '-';
                                $masuk = $absen->waktu_masuk
                                    ? \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i')
                                    : '-';
                                $pulang = $absen->waktu_pulang
                                    ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                                    : '-';
                                $badgeClass = match ($absen->status) {
                                    'Hadir' => 'success',
                                    'Telat' => 'warning',
                                    'Izin' => 'primary',
                                    'Sakit' => 'secondary',
                                    'Alpha' => 'danger',
                                    default => 'light',
                                };
                                $textClassMasuk = $absen->status === 'Telat' ? 'text-warning' : 'text-success';
                            @endphp
                            <tr data-uid="{{ $uid }}" data-tipe="siswa">
                                <td>{{ $no++ }}</td>
                                <td>{{ $nama }}</td>
                                <td>{{ $tipe }}</td>
                                <td>{{ $uid }}</td>
                                <td>{{ $kelas }}</td>
                                <td class="{{ $textClassMasuk }}">{{ $masuk }}</td>
                                <td>{{ $pulang }}</td>
                                <td><span class="badge bg-{{ $badgeClass }}">{{ $absen->status }}</span></td>
                            </tr>
                        @endforeach

                        @foreach ($absenGuru as $absen)
                            @php
                                $guru = $absen->guru;
                                $nama = $guru->nama;
                                $tipe = 'Guru';
                                $uid = $guru->uid;
                                $kelas = '-';
                                $masuk = $absen->waktu_masuk
                                    ? \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i')
                                    : '-';
                                $pulang = $absen->waktu_pulang
                                    ? \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i')
                                    : '-';
                                $badgeClass = match ($absen->status) {
                                    'Hadir' => 'success',
                                    'Telat' => 'warning',
                                    'Izin' => 'primary',
                                    'Sakit' => 'secondary',
                                    'Alpha' => 'danger',
                                    default => 'light',
                                };
                                $textClassMasuk = $absen->status === 'Telat' ? 'text-warning' : 'text-success';
                            @endphp
                            <tr data-uid="{{ $uid }}" data-tipe="guru">
                                <td>{{ $no++ }}</td>
                                <td>{{ $nama }}</td>
                                <td>{{ $tipe }}</td>
                                <td>{{ $uid }}</td>
                                <td>{{ $kelas }}</td>
                                <td class="{{ $textClassMasuk }}">{{ $masuk }}</td>
                                <td>{{ $pulang }}</td>
                                <td><span class="badge bg-{{ $badgeClass }}">{{ $absen->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        // ⏰ Menampilkan jam real-time
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
        }, 1000);

        const form = document.getElementById('absenForm');
        const uidInput = document.getElementById('uidInput');
        uidInput.focus();

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const uid = uidInput.value.trim();
            if (!uid) return;

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        uid: uid
                    })
                })
                .then(async res => {
                    const text = await res.text();
                    console.log("📥 Response text:", text);

                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error("❌ Gagal parsing JSON:", e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Response bukan format JSON yang valid.'
                        });
                        return;
                    }

                    uidInput.value = '';
                    uidInput.focus();

                    if (data.status === 'success') {
                        // ✅ Notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // ✅ Update panel kiri
                        document.getElementById('fotoPengguna').src = data.foto ??
                            '{{ asset('assets/images/default.png') }}';
                        document.getElementById('namaPengguna').textContent = data.nama ?? '-';
                        document.getElementById('uidPengguna').textContent = 'UID: ' + (data.uid ?? '-');
                        document.getElementById('tipePengguna').textContent = 'Tipe: ' + (data.tipe ===
                            'guru' ? 'Guru' : 'Siswa');

                        // ✅ Hapus baris lama jika ada (berdasarkan UID dan tipe)
                        const table = document.getElementById('absenTabel');
                        const tipe = data.tipe.toLowerCase(); // pastikan lowercase
                        const existingRow = table.querySelector(
                            `tr[data-uid="${data.uid}"][data-tipe="${tipe}"]`);
                        if (existingRow) existingRow.remove();

                        // ✅ Buat baris baru
                        const row = document.createElement('tr');
                        row.className = `status-${data.absen_status}`; // misal: status-Hadir
                        row.setAttribute('data-uid', data.uid);
                        row.setAttribute('data-tipe', tipe);

                        const cells = [
                            '', // nomor akan diisi ulang nanti
                            data.nama,
                            data.tipe === 'guru' ? 'Guru' : 'Siswa',
                            data.uid,
                            data.kelas ?? '-',
                            data.waktu_masuk ?? '-',
                            data.waktu_pulang ?? '-',
                            data.absen_status
                        ];

                        cells.forEach(val => {
                            const td = document.createElement('td');
                            td.textContent = val;
                            row.appendChild(td);
                        });

                        table.prepend(row);

                        // ✅ Update nomor urut semua baris
                        const allRows = table.querySelectorAll('tr');
                        allRows.forEach((r, i) => {
                            if (r.cells.length) r.cells[0].textContent = i + 1;
                        });

                    } else {
                        // ❗ Gagal absensi
                        Swal.fire({
                            icon: data.status === 'info' ? 'info' : 'error',
                            title: 'Perhatian',
                            text: data.message
                        });
                    }
                })
                .catch(err => {
                    console.error('❌ Gagal:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat memproses absensi.'
                    });
                });
        });
    </script>

</body>

</html>
