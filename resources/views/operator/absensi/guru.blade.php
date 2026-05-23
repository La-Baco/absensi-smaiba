@extends('layouts.app')

@section('title', 'Absensi Guru')

@section('content')
<section class="section">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Absensi Guru</h3>
                <p class="text-subtitle text-muted">Data Absensi Guru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Absensi Guru</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        Absensi Guru - Tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}
                    </h4>
                </div>

                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guru as $item)
                                    @php
                                        $absen = $absensiGuru[$item->id] ?? null;
                                        $status = $absen->status ?? '-';
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
                                        <td class="text-center">{{ $item->nama }}</td>
                                        <td class="text-center">
                                            @if ($status !== '-')
                                                <span class="badge bg-{{ $warna[$status] ?? 'secondary' }}">
                                                    {{ $status }}
                                                </span>
                                            @else
                                                <span class="text-muted">Belum absen</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning btn-edit-status-guru"
                                                data-id-guru="{{ $item->id }}"
                                                data-nama="{{ $item->nama }}"
                                                data-status="{{ $status }}"
                                                data-tanggal="{{ $tanggal }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditStatusGuru">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($guru->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data guru.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal edit status guru --}}
    <div class="modal fade" id="modalEditStatusGuru" tabindex="-1" aria-labelledby="modalEditStatusGuruLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('operator.absensi.guru.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id_guru" id="edit-id-guru">
                <input type="hidden" name="tanggal" id="edit-tanggal-guru">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Absensi Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nama-guru" class="form-label">Nama Guru</label>
                            <input type="text" id="edit-nama-guru" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status-guru" class="form-label">Status</label>
                            <select name="status" id="edit-status-guru" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Telat">Telat</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
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
        const buttons = document.querySelectorAll('.btn-edit-status-guru');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const idGuru = this.getAttribute('data-id-guru');
                const nama = this.getAttribute('data-nama');
                const status = this.getAttribute('data-status');
                const tanggal = this.getAttribute('data-tanggal');

                document.getElementById('edit-id-guru').value = idGuru;
                document.getElementById('edit-nama-guru').value = nama;
                document.getElementById('edit-status-guru').value = status;
                document.getElementById('edit-tanggal-guru').value = tanggal;
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        @endif
    });
</script>
@endsection

@endsection
