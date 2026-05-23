@extends('layouts.app')

@section('title', 'Profil Guru')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil Guru</h3>
                <p class="text-subtitle text-muted">Halaman detail informasi lengkap guru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Guru</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $guru->nama }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            {{-- Kolom Kiri --}}
            <div class="col-12 col-lg-4">
                {{-- Profil --}}
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <div class="avatar avatar-2xl mx-auto">
                            @if ($guru->foto)
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($guru->nama) }}" alt="Avatar" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                        </div>

                        <h4 class="mt-3">{{ $guru->nama }}</h4>
                        <p class="text-muted">NIP: {{ $guru->nip }}</p>
                        <span class="badge bg-{{ $guru->aktif ? 'success' : 'secondary' }}">
                            {{ $guru->aktif ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>

                {{-- Kartu Identitas --}}
                <div class="card id-card shadow" style="font-size: 0.6rem; background: linear-gradient(135deg, #005c97 0%, #363795 100%); color: white; height: 270px; border-radius: 15px;">
                    <div class="p-4 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="small">SMA Islam Baiturrahman</div>
                                <div class="h5 fw-bold mb-0 text-white">KARTU TANDA GURU</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="photo-placeholder border border-white d-flex align-items-center justify-content-center text-white text-opacity-50" style="width: 80px; height: 100px; border-style: dashed;">
                                    @if ($guru->foto)
                                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto" class="img-fluid" style="width: 80px; height: 100px; object-fit: cover;">
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
                                <div class="border-bottom border-white-50 py-1">{{ $guru->nama }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">UID</div>
                                <div class="border-bottom border-white-50 py-1">{{ $guru->uid }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">NIP</div>
                                <div class="border-bottom border-white-50 py-1">{{ $guru->nip }}</div>
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

            {{-- Kolom Kanan --}}
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4 text-dark">Biodata</h5>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <strong>Nama Lengkap:</strong>
                                <p class="text-muted">{{ $guru->nama }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>NIP:</strong>
                                <p class="text-muted">{{ $guru->nip }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>UID Kartu:</strong>
                                <p class="text-muted">{{ $guru->uid }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>Jenis Kelamin:</strong>
                                <p class="text-muted">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>Tempat & Tanggal Lahir:</strong>
                                <p class="text-muted">
                                    {{ $guru->tempat_lahir ?? '-' }},
                                    {{ $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Alamat:</strong>
                                <p class="text-muted">{{ $guru->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('guru.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
