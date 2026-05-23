@extends('layouts.app')

@section('title', 'Manajemen Hari Libur')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
    <style>
        #calendar {
            min-height: 500px;
        }
    </style>
@endsection

@section('content')
    <div class="page-heading">
        <h3>Kelender Akademik Sekolah</h3>
    </div>

    <section class="section">
        {{-- Kalender --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        {{-- Modal Tambah/Edit Libur --}}
        <div class="modal fade" id="liburModal" tabindex="-1" aria-labelledby="liburModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="formLibur" method="POST" action="{{ route('libur.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="liburModalLabel">Tambah Hari Libur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_libur" class="form-label">Nama Libur</label>
                                <input type="text" name="nama_libur" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" id="tanggal_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" id="tanggal_selesai"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data Libur --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Daftar Hari Libur</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Libur</th>
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Tanggal Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($libur as $i => $item)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="text-center">{{ $item->nama_libur }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                </td>
                                <td class="text-center"> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning btn-edit-libur" data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_libur }}" data-mulai="{{ $item->tanggal_mulai }}"
                                        data-selesai="{{ $item->tanggal_selesai }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form id="formDelete{{ $item->id }}"
                                        action="{{ route('libur.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $item->id }}"><i class="bi bi-trash"></i></button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        @if ($libur->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data libur</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- Modal Edit Hari Libur -->
    <div class="modal fade" id="editLiburModal" tabindex="-1" aria-labelledby="editLiburModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditLibur" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLiburModalLabel">Edit Hari Libur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_libur" class="form-label">Nama Libur</label>
                            <input type="text" name="nama_libur" id="edit_nama_libur" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('vendor/fullcalendar/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                dateClick: function (info) {
                    document.getElementById('formLibur').reset();
                    document.getElementById('tanggal_mulai').value = info.dateStr;
                    document.getElementById('tanggal_selesai').value = info.dateStr;
                    new bootstrap.Modal(document.getElementById('liburModal')).show();
                },
                events: {!! $libur->map(function ($l) {
                    return [
                        'title' => $l->nama_libur,
                        'start' => $l->tanggal_mulai,
                        'end'   => \Carbon\Carbon::parse($l->tanggal_selesai)->addDay()->format('Y-m-d'),
                    ];
                })->values()->toJson() !!}
            });

            calendar.render();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event tombol edit
            document.querySelectorAll('.btn-edit-libur').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const mulai = this.dataset.mulai;
                    const selesai = this.dataset.selesai;

                    // Set form action
                    const form = document.getElementById('formEditLibur');
                    form.action = `/admin/libur/${id}`;

                    // Isi input form
                    document.getElementById('edit_nama_libur').value = nama;
                    document.getElementById('edit_tanggal_mulai').value = mulai;
                    document.getElementById('edit_tanggal_selesai').value = selesai;

                    // Tampilkan modal
                    new bootstrap.Modal(document.getElementById('editLiburModal')).show();
                });
            });
        });
        </script>


<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
        @php
            session()->forget('success');
        @endphp
    @endif

    {{-- Notifikasi error --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
        @php
            session()->forget('error');
        @endphp
    @endif
    {{-- Konfirmasi hapus siswa --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const siswaId = this.dataset.id;

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('formDelete' + siswaId).submit();
                        }
                    });
                });
            });
        });
    </script>



@endsection
