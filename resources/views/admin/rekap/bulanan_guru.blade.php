    @extends('layouts.app')

    @section('title', 'Rekap Absensi Bulanan Guru')

    @section('content')
        <div class="page-heading">
            <h3>Rekap Absensi Bulanan Guru</h3>
        </div>

        <section class="section">
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="bulan">Pilih Bulan</label>
                        <input type="month" name="bulan" id="bulan" value="{{ $bulan }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary me-2">Tampilkan</button>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Rekap Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
                    </h4>
                    <a href="{{ route('rekap.guru.bulanan.export', ['format' => 'pdf', 'bulan' => $bulan]) }}"
                    class="btn btn-danger btn-sm me-2 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-fill"></i> PDF
                    </a>
                    <a href="{{ route('rekap.guru.bulanan.export', ['format' => 'excel', 'bulan' => $bulan]) }}"
                    class="btn btn-success btn-sm me-2 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Guru</th>
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
                                    <td>{{ $r['guru']->nama }}</td>
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

                                            if ($status === 'Alpha') $totalAlpha++;
                                            if ($status === 'Hadir') $totalHadir++;
                                            if ($status === 'Telat') $totalTelat++;
                                            if ($status === 'Izin') $totalIzin++;
                                            if ($status === 'Sakit') $totalSakit++;
                                        @endphp

                                        <td class="text-center {{ $warna }}">
                                            @if ($clickable)
                                                <button class="btn btn-sm fw-bold text-white btn-edit-status"
                                                        style="background: transparent; border: none; width: 100%; height: 100%;"
                                                        data-id="{{ $r['guru']->id }}"
                                                        data-nama="{{ $r['guru']->nama }}"
                                                        data-tanggal="{{ $tanggal }}"
                                                        data-status="{{ $status }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editStatusModalGuru">
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
                                    <td colspan="{{ count($tanggalList) + 6 }}" class="text-center">Tidak ada data</td>
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


        <!-- Modal Ubah Status Guru -->
<div class="modal fade" id="editStatusModalGuru" tabindex="-1" aria-labelledby="editStatusModalGuruLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditStatusGuru">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Absensi Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_guru" id="modal-id-guru">
                    <input type="hidden" name="tanggal" id="modal-tanggal-guru">
                    <div class="mb-2">
                        <label>Nama Guru</label>
                        <input type="text" id="modal-nama-guru" class="form-control" disabled>
                    </div>
                    <div class="mb-2">
                        <label>Status</label>
                        <select name="status" id="modal-status-guru" class="form-control" required>
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
    const modalGuru = new bootstrap.Modal(document.getElementById('editStatusModalGuru'));

    // Saat tombol edit diklik
    document.querySelectorAll('.btn-edit-status').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modal-id-guru').value = btn.dataset.id;
            document.getElementById('modal-tanggal-guru').value = btn.dataset.tanggal;
            document.getElementById('modal-nama-guru').value = btn.dataset.nama;
            document.getElementById('modal-status-guru').value = btn.dataset.status;
        });
    });

    // Saat form dikirim
    document.getElementById('formEditStatusGuru').addEventListener('submit', function(e) {
        e.preventDefault();

        const idGuru = document.getElementById('modal-id-guru').value;
        const tanggal = document.getElementById('modal-tanggal-guru').value;
        const status = document.getElementById('modal-status-guru').value;

        fetch("{{ route('rekap.guru.bulanan.updateStatus') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_guru: idGuru,
                tanggal: tanggal,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalGuru.hide();
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status absensi guru berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message || 'Gagal memperbarui status.',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error(error);
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
