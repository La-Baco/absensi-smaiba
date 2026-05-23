@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Siswa Kelas {{ $kelas->nama_kelas }}</h4>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Siswa</th>
                                    <th class="text-center">NIS</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas->siswa as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $siswa->nama }}</td>
                                        <td class="text-center">{{ $siswa->nis }}</td>
                                        <td class="text-center">{{ $siswa->jenis_kelamin }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer text-start">
                    <a href="{{ route('kelas.index') }}"
                       class="btn btn-secondary btn-sm d-inline-flex align-items-center px-3 py-2">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
