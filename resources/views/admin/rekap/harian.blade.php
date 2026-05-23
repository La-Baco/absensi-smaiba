@extends('layouts.app')

@section('title', 'Rekap Harian')

@section('content')
<div class="page-heading">
    <h3>Rekap Absensi Harian</h3>
</div>

<section class="section">
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}">
            <button class="btn btn-primary" type="submit">Tampilkan</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <h5>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $absen)
                        <tr>
                            <td>{{ $absen->siswa->nama }}</td>
                            <td>{{ $absen->siswa->kelas->nama }}</td>
                            <td>{{ $absen->waktu_masuk }}</td>
                            <td>{{ $absen->waktu_pulang }}</td>
                            <td>{{ $absen->status }}</td>
                            <td>{{ $absen->keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
