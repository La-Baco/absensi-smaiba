@extends('layouts.app')

@section('title', 'Rekap Absensi Bulanan')

@section('content')
    <div class="page-heading">
        <h3>Rekap Absensi Bulanan</h3>
    </div>

    <section class="section">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <label for="bulan">Pilih Bulan</label>
                    <input type="month" name="bulan" id="bulan" value="{{ $bulan }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="kelas">Pilih Kelas</label>
                    <select name="kelas" id="kelas" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelas->id == $kelasId ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Tampilkan</button>



                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">Rekap Bulan:
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</h4>
                <a href="{{ route('rekap.bulanan.export', ['format' => 'pdf', 'bulan' => $bulan, 'kelas' => $kelasId]) }}"
                    class="btn btn-danger btn-sm me-2 d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-file-earmark-fill"></i>
                    <span>PDF</span>
                </a>
                <a href="{{ route('rekap.bulanan.export', ['format' => 'excel', 'bulan' => $bulan, 'kelas' => $kelasId]) }}"
                    class="btn btn-success btn-sm me-2 d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    <span>Excel</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                @foreach ($tanggalList as $tanggal)
                                    <th>{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</th>
                                @endforeach
                                <th>Total Alpha</th>
                                <th>Total Hadir</th>
                                <th>Total Telat</th>
                                <th>Total Izin</th>
                                <th>Total Sakit</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach ($rekap as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r['siswa']->nama }}</td>
            @php
            $totalAlpha = $totalHadir = $totalTelat = $totalIzin = $totalSakit = 0;
        @endphp
            @foreach ($tanggalList as $tanggal)
                @php
                    $absen = $r['absen'][$tanggal];
                    $kode = $absen['kode'];
                    $warna = $absen['warna'];
                    $status = $absen['status'];
                    $clickable = $absen['clickable'];

                    // Hitung total
                    if ($status === 'Alpha') $totalAlpha++;
                    if ($status === 'Hadir') $totalHadir++;
                    if ($status === 'Telat') $totalTelat++;
                    if ($status === 'Izin') $totalIzin++;
                    if ($status === 'Sakit') $totalSakit++;
                @endphp

                <td class="text-center {{ $warna }}">
                    @if ($clickable)
                        <button
                            class="btn btn-sm fw-bold text-white btn-edit-status"
                            style="background: transparent; border: none; width: 100%; height: 100%;"
                            data-id="{{ $r['siswa']->id }}"
                            data-nama="{{ $r['siswa']->nama }}"
                            data-tanggal="{{ $tanggal }}"
                            data-status="{{ $status }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editStatusModal">
                            {{ $kode }}
                        </button>
                    @else
                        <span class="fw-bold text-white">{{ $kode }}</span>
                    @endif
                </td>
            @endforeach
            <td class="text-center fw-bold">{{ $totalAlpha }}</td>
            <td class="text-center fw-bold">{{ $totalHadir }}</td>
            <td class="text-center fw-bold">{{ $totalTelat }}</td>
            <td class="text-center fw-bold">{{ $totalIzin }}</td>
            <td class="text-center fw-bold">{{ $totalSakit }}</td>
        </tr>
    @endforeach

    @if (count($rekap) === 0)
        <tr>
            <td colspan="{{ count($tanggalList) + 5 }}" class="text-center">Tidak ada data</td>
        </tr>
    @endif
</tbody>





                    </table>
                </div>

                <div class="mt-3 small">
                    <strong>Keterangan:</strong>
                    <span class="badge bg-success">H = Hadir</span>
                    <span class="badge bg-warning text-dark">T = Telat</span>
                    <span class="badge bg-primary">S = Sakit</span>
                    <span class="badge bg-primary">I = Izin</span>
                    <span class="badge bg-danger">A = Alpha</span>
                </div>
            </div>


        </div>
    </section>
    <!-- Modal Ubah Status -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditStatus">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_siswa" id="modal-id-siswa">
                        <input type="hidden" name="tanggal" id="modal-tanggal">
                        <div class="mb-2">
                            <label>Nama Siswa</label>
                            <input type="text" id="modal-nama" class="form-control" disabled>
                        </div>
                        <div class="mb-2">
                            <label>Status</label>
                            <select name="status" id="modal-status" class="form-control" required>
                                <option value="Hadir">Hadir (H)</option>
                                <option value="Telat">Telat (T)</option>
                                <option value="Sakit">Sakit (S)</option>
                                <option value="Izin">Izin (I)</option>
                                <option value="Alpha">Alpha (A)</option>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentButton = null;

            document.querySelectorAll('.btn-edit-status').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentButton = btn;

                    document.getElementById('modal-id-siswa').value = btn.dataset.id;
                    document.getElementById('modal-tanggal').value = btn.dataset.tanggal;
                    document.getElementById('modal-status').value = btn.dataset.status;
                    document.getElementById('modal-nama').value = btn.dataset.nama;
                });
            });

            document.getElementById('formEditStatus').addEventListener('submit', function(e) {
                e.preventDefault();

                const idSiswa = document.getElementById('modal-id-siswa').value;
                const tanggal = document.getElementById('modal-tanggal').value;
                const status = document.getElementById('modal-status').value;


                fetch('/admin/rekap/bulanan/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_siswa: idSiswa,
                            tanggal: tanggal,
                            status: status
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Tutup modal
                            bootstrap.Modal.getInstance(document.getElementById('editStatusModal'))
                                .hide();

                            // Tampilkan SweetAlert
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Status absensi berhasil diperbarui.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload halaman setelah klik OK
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal memperbarui status.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengirim data.',
                            icon: 'error'
                        });
                    });
            });
        });
    </script>
@endsection
