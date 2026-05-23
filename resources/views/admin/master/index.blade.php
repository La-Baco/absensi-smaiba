@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('css')
<style>
    .dataTable-top {
    display: flex;
    justify-content: space-between; /* Search di kanan, Quantity di kiri */
    align-items: center;
}

</style>
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master</h3>
                    <p class="text-subtitle text-muted">Data Siswa dan Guru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Simple Datatable
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>UID</th>
                                <th>Kelas / Jabatan</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data Siswa --}}
                            @foreach ($siswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->uid }}</td>
                                    <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $item->alamat ?? '-'}}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->aktif ? 'success' : 'danger' }}">
                                            {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Data Guru --}}
                            @foreach ($guru as $item)
                                <tr>
                                    <td>{{ $loop->iteration + $siswa->count() }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->uid }}</td>
                                    <td>Guru</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $item->alamat ?? '-' }} </td>
                                    <td>
                                        <span class="badge bg-{{ $item->aktif ? 'success' : 'danger' }}">
                                            {{ $item->aktif ? 'Aktif' : 'Noaktif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endsection

    @section('js')
        <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
        <script>
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);
        </script>
    @endsection
