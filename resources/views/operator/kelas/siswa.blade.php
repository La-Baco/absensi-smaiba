@extends('layouts.app')

@section('title', 'Daftar Siswa - ' . $kelas->nama)

@section('content')
    <section class="section">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Daftar Siswa - {{ $kelas->nama_kelas }}</h4>

                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">NIS</th>
                                        <th class="text-center">UID</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswaList as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->nis }}</td>
                                            <td class="text-center">{{ $item->uid }}</td>
                                            <td class="text-center">{{ $item->nama }}</td>
                                            <td class="text-center">{{ $item->jenis_kelamin }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Tidak ada siswa dalam kelas
                                                ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="m-3 text-start">
                            <a href="{{ route('operator.kelas.index') }}"
                                class="btn btn-secondary btn-sm d-inline-flex align-items-center px-3 py-2">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
