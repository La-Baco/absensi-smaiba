@extends('layouts.app')

@section('title', 'Waktu Absensi')

@section('content')
<div class="page-heading">
    <h3>Manajemen Waktu Absensi</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pengaturan Waktu Absensi (Senin - Sabtu)</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('waktu_absensi.update_all') }}" id="formPengaturanWaktu">
                @csrf
                <div class="row">
                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                        @php
                            $data = $waktu->firstWhere('hari', $day);
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3 h-100">
                                <h6>{{ $day }}</h6>
                                <input type="hidden" name="data[{{ $day }}][hari]" value="{{ $day }}">
                                <div class="mb-2">
                                    <label>Jam Masuk</label>
                                    <div class="d-flex gap-2">
                                        <input type="time" name="data[{{ $day }}][jam_masuk_mulai]" class="form-control" value="{{ $data->jam_masuk_mulai ?? '' }}">
                                        <input type="time" name="data[{{ $day }}][jam_masuk_akhir]" class="form-control" value="{{ $data->jam_masuk_akhir ?? '' }}">
                                    </div>
                                </div>
                                <div>
                                    <label>Jam Pulang</label>
                                    <div class="d-flex gap-2">
                                        <input type="time" name="data[{{ $day }}][jam_pulang_mulai]" class="form-control" value="{{ $data->jam_pulang_mulai ?? '' }}">
                                        <input type="time" name="data[{{ $day }}][jam_pulang_akhir]" class="form-control" value="{{ $data->jam_pulang_akhir ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol aksi --}}
                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" id="btnReset">Reset Waktu</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Pengaturan</button>
                </div>
            </form>

            {{-- Form reset hidden --}}
            <form method="POST" id="formReset" action="{{ route('waktu_absensi.reset') }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resetBtn = document.getElementById('btnReset');
        const simpanForm = document.getElementById('formPengaturanWaktu');
        const resetForm = document.getElementById('formReset');

        if (resetBtn && resetForm) {
            resetBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Reset semua waktu?',
                    text: 'Semua pengaturan waktu akan dihapus dari database.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetForm.submit();
                    }
                });
            });
        }

        if (simpanForm) {
            simpanForm.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan pengaturan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Gunakan setTimeout agar SweetAlert tidak mengganggu form submit
                        setTimeout(() => {
                            simpanForm.submit();
                        }, 200);
                    }
                });
            });
        }
    });
</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            showConfirmButton: false,
            timer: 2000
        });
    });
</script>
@endif
@endsection

