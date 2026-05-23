@extends('layouts.app')

@section('title', 'Waktu Absensi')

@section('content')
<section class="section">
    <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Waktu Absensi</h3>
                    <p class="text-subtitle text-muted">Waktu absensi Senin - Sabtu</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Waktu Absensi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

    @php
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    @endphp

    <div class="row">
        @foreach ($hariList as $hari)
            @php
                $waktu = $waktuList->firstWhere('hari', $hari);
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-calendar-week me-2 text-primary"></i>{{ $hari }}
                        </h5>

                        {{-- Jam Masuk --}}
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Masuk Mulai</small><br>
                                <span class="badge bg-light-primary text-dark">
                                    {{ $waktu ? \Carbon\Carbon::parse($waktu->jam_masuk_mulai)->format('H:i') : '-' }}
                                </span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Masuk Akhir</small><br>
                                <span class="badge bg-light-warning text-dark">
                                    {{ $waktu ? \Carbon\Carbon::parse($waktu->jam_masuk_akhir)->format('H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-2">

                        {{-- Jam Pulang --}}
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Pulang Mulai</small><br>
                                <span class="badge bg-light-primary text-dark">
                                    {{ $waktu ? \Carbon\Carbon::parse($waktu->jam_pulang_mulai)->format('H:i') : '-' }}
                                </span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Pulang Akhir</small><br>
                                <span class="badge bg-light-danger text-dark">
                                    {{ $waktu ? \Carbon\Carbon::parse($waktu->jam_pulang_akhir)->format('H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($waktuList->isEmpty())
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Tidak ada data waktu absensi.
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
