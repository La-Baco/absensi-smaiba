@extends('layouts.app')

@section('title', 'Absensi Siswa')

@section('content')
<section class="section">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Absensi Siswa</h3>
                <p class="text-subtitle text-muted">Data absensi siswa hari ini</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Absensi Siswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Filter Kelas -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Absensi Siswa - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h4>
            <form action="{{ route('operator.absensi.siswa') }}" method="GET" class="d-flex align-items-center">
                <select name="kelas_id" class="form-select me-2">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Status Hari Ini</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaList as $siswa)
                            @php
                                $absen = $absensiHariIni[$siswa->id] ?? null;
                                $status = $absen->status ?? null;
                                $warna = [
                                    'Hadir' => 'success',
                                    'Telat' => 'warning',
                                    'Izin' => 'info',
                                    'Sakit' => 'primary',
                                    'Alpha' => 'danger',
                                ];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td class="text-center">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($status)
                                        <span class="badge bg-{{ $warna[$status] ?? 'secondary' }}">{{ $status }}</span>
                                    @else
                                        <span class="text-muted">Belum absen</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning btn-edit-status-siswa"
                                        data-id-siswa="{{ $siswa->id }}"
                                        data-nama="{{ $siswa->nama }}"
                                        data-status="{{ $status ?? '' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditStatusSiswa">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Silakan pilih kelas untuk melihat data siswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Status -->
    <div class="modal fade" id="modalEditStatusSiswa" tabindex="-1" aria-labelledby="modalEditStatusSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('operator.absensi.siswa.update') }}" method="POST" id="formEditStatusSiswa">
                @csrf
                <input type="hidden" name="id_siswa" id="edit-id-siswa">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditStatusSiswaLabel">Ubah Status Absensi Siswa (Hari Ini)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nama-siswa" class="form-label">Nama Siswa</label>
                            <input type="text" id="edit-nama-siswa" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status-siswa" class="form-label">Status</label>
                            <select name="status" id="edit-status-siswa" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Telat">Telat</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                            </select>
                        </div>
                        <small class="text-muted">Perubahan ini berlaku untuk tanggal hari ini.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tombol edit
    document.querySelectorAll('.btn-edit-status-siswa').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit-id-siswa').value = this.dataset.idSiswa;
            document.getElementById('edit-nama-siswa').value = this.dataset.nama;
            document.getElementById('edit-status-siswa').value = this.dataset.status || '';
        });
    });

    // SweetAlert notifikasi
    @if (session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}' });
    @endif
    @if (session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}' });
    @endif
});
</script>
@endsection
@endsection
