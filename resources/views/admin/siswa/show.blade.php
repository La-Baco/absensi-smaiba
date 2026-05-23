@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Profil Siswa</h3>
                    <p class="text-subtitle text-muted">Halaman detail informasi lengkap siswa</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $siswa->nama }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                {{-- Kolom Kiri: Profil & Kartu Identitas --}}
                <div class="col-12 col-lg-4">
                    {{-- Profil --}}
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <div class="avatar avatar-2xl mx-auto">
                                @if ($siswa->foto)
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa"
                                        class="img-fluid rounded-circle"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}" alt="Avatar"
                                        class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                @endif
                            </div>

                            <h4 class="mt-3">{{ $siswa->nama }}</h4>
                            <p class="text-muted">NIS :{{ $siswa->nis }}</p>
                            <span class="badge bg-{{ $siswa->aktif ? 'success' : 'secondary' }}">
                                {{ $siswa->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Kartu Identitas --}}
                    <div class="card id-card shadow"
                        style="font-size: 0.6rem; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; height: 270px; border-radius: 15px;">
                        <div class="p-4 h-100 d-flex flex-column justify-content-between"
                            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; height: 270px; border-radius: 15px;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="small">SMA Islam Baiturrahman</div>
                                    <div class="h5 fw-bold mb-0 text-white">KARTU TANDA SISWA</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="photo-placeholder border border-white d-flex align-items-center justify-content-center text-white text-opacity-50"
                                        style="width: 80px; height: 100px; border-style: dashed;">
                                        @if ($siswa->foto)
                                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto"
                                                class="img-fluid" style="width: 80px; height: 100px; object-fit: cover;">
                                        @else
                                            FOTO
                                        @endif
                                    </div>
                                    <div class="text-end small">
                                        <div class="fw-bold">EXP:</div>
                                        <div class="fw-light">12/2028</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-3 mb-2">
                                <div class="col-6">
                                    <div class="small text-white-50">NAMA</div>
                                    <div class="border-bottom border-white-50 py-1">{{ $siswa->nama }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-white-50">UID</div>
                                    <div class="border-bottom border-white-50 py-1">{{ $siswa->uid }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-white-50">KELAS</div>
                                    <div class="border-bottom border-white-50 py-1">{{ $siswa->kelas->nama_kelas ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-white-50">NIS</div>
                                    <div class="border-bottom border-white-50 py-1">{{ $siswa->nis }}</div>
                                </div>
                            </div>

                            <div class="barcode-placeholder text-center py-1 bg-dark bg-opacity-50">
                                <small class="text-white">Scan Card Here</small>
                            </div>

                            <div class="d-flex justify-content-between small mt-1 text-white-50">
                                <div>DIBUAT OLEH: ADMIN</div>
                                <div>HANYA UNTUK INTERNAL</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Biodata --}}
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-4 text-dark">Biodata</h5>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <strong>Nama Lengkap:</strong>
                                    <p class="text-muted">{{ $siswa->nama }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>NIS:</strong>
                                    <p class="text-muted">{{ $siswa->nis }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>Kelas:</strong>
                                    <p class="text-muted">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>UID Kartu:</strong>
                                    <p class="text-muted">{{ $siswa->uid }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>Jenis Kelamin:</strong>
                                    <p class="text-muted">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>Tempat & Tanggal Lahir:</strong>
                                    <p class="text-muted">
                                        {{ $siswa->tempat_lahir ?? '-' }},
                                        {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong>Alamat:</strong>
                                    <p class="text-muted">{{ $siswa->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
