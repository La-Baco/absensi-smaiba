@extends('layouts.app')

@section('title', 'Rekap Mingguan')

@section('content')
<div class="page-heading">
    <h3>Rekap Absensi Mingguan</h3>
</div>

<section class="section">
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label>Pilih Minggu (Tanggal)</label>
                <input type="week" name="minggu" class="form-control" value="{{ request('minggu') }}">
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <h5>Periode: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Total Hadir</th>
                        <th>Telat</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekap as $item)
                        <tr>
                            <td>{{ $item['siswa']->nama }}</td>
                            <td>{{ $item['siswa']->kelas->nama_kelas }}</td>
                            <td>{{ $item['hadir'] }}</td>
                            <td>{{ $item['telat'] }}</td>
                            <td>{{ $item['izin'] }}</td>
                            <td>{{ $item['sakit'] }}</td>
                            <td>{{ $item['alpha'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
