@extends('layouts.app')

@section('title', 'Daftar Kelas')

@section('css')
    <style>
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background-color: #004085;
            border-color: #004085;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Kelas</h3>
                    <p class="text-subtitle text-muted">Data Siswa dan Guru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">

            <div class="row">
                @forelse ($kelasList as $item)
                    <div class="col-md-6 col-sm-12 mb-4">
                        <div class="card h-100 shadow-sm rounded-3"
                            style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-content p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h2 class="card-title text-center mb-3 fw-bold"
                                        style="font-size: 3.5rem; letter-spacing: 0.1em;">
                                        {{ $item->nama_kelas }}
                                    </h2>
                                    <p class="text-center text-muted mb-4 fs-5">
                                        Jumlah Siswa: <span class="fw-semibold">{{ $item->siswa_count }}</span>
                                    </p>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('operator.kelas.siswa', $item->id) }}"
                                        class="btn btn-primary px-4 py-2" style="transition: background-color 0.3s ease;">
                                        <i class="bi bi-eye me-2"></i> Lihat Siswa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        Belum ada data kelas.
                    </div>
                @endforelse
            </div>
        </section>




    @endsection
