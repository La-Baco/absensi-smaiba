@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-heading">
        <h3>DASHBOARD</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldUser"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Siswa</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalSiswa }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldWork"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Guru</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalGuru }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Kelas</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalKelas }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldShield-Done"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Administrasi</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalUsers }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kehadiran 1 Minggu Terakhir</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-kehadiran"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Hari Libur</h4>
                            </div>
                            <div class="card-body">
                                @if ($liburMendatang->count() > 0)
                                    <ul class="list-group">
                                        @foreach ($liburMendatang as $libur)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $libur->nama_libur }}</span>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($libur->tanggal_mulai)->translatedFormat('d M Y') }}
                                                    @if ($libur->tanggal_selesai && $libur->tanggal_selesai != $libur->tanggal_mulai)
                                                        -
                                                        {{ \Carbon\Carbon::parse($libur->tanggal_selesai)->translatedFormat('d M Y') }}
                                                    @endif
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Tidak ada libur dalam waktu dekat.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
    <div class="card">
        <div class="card-header">
            <h4>Absensi Terbaru</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @forelse($absensiTerbaru as $absen)
            <tr>
                <td class="col-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md">
                            @if($absen->siswa)
                                <img src="{{ $absen->siswa->foto ? asset('storage/'.$absen->siswa->foto) : asset('assets/images/faces/default.jpg') }}" alt="Foto Siswa">
                            @elseif($absen->guru)
                                <img src="{{ $absen->guru->foto ? asset('storage/'.$absen->guru->foto) : asset('assets/images/faces/default.jpg') }}" alt="Foto Guru">
                            @else
                                <img src="{{ asset('assets/images/faces/default.jpg') }}" alt="Foto Default">
                            @endif
                        </div>
                        <p class="font-bold ms-3 mb-0">
                            {{ $absen->siswa?->nama ?? $absen->guru?->nama ?? '-' }}
                        </p>
                    </div>
                </td>

                <td class="col-3">
                    <span class="badge
                        @if ($absen->status == 'Hadir') bg-success
                        @elseif($absen->status == 'Izin') bg-info
                        @elseif($absen->status == 'Sakit') bg-info
                        @elseif($absen->status == 'Telat') bg-warning
                        @elseif($absen->status == 'Alpha') bg-danger
                        @else bg-danger @endif">
                        {{ $absen->status }}
                    </span>
                </td>

                <td class="col-3">
                    {{ $absen->waktu_masuk ?? $absen->waktu_pulang ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data absensi terbaru</td>
            </tr>
        @endforelse
    </tbody>
</table>

            </div>
        </div>
    </div>
</div>

                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('assets/images/faces/2.jpg')}}" alt="user">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                <h6 class="text-muted mb-0">{{ ucfirst(Auth::user()->role) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
    <div class="card-header">
        <h4>Data Kelas</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasJumlahSiswa as $kelas)
                    <tr>
                        <td>{{ $kelas->nama_kelas }}</td>
                        <td>{{ $kelas->jumlah_siswa }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
    <script>
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Hadir',
                data: @json($values)
            }],
            xaxis: {
                categories: @json($labels)
            },
            colors: ['#00E396'],
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: true
            },
            markers: {
                size: 5
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart-kehadiran"), options);
        chart.render();
    </script>
@endsection
