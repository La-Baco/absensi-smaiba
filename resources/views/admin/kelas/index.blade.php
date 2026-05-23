@extends('layouts.app')

@section('title', 'Management Kelas')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Data Kelas</h3>
            <p class="text-subtitle text-muted">Halaman informasi lengkap Kelas</p>
        </div>
    </div>
</div>
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Kelas</h4>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKelas"> Tambah Kelas </button>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Kelas</th>
                                    <th class="text-center">Jumlah Siswa</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->nama_kelas }}</td>
                                        <td class="text-center">{{ $item->siswa_count }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('kelas.show', $item->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $item->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form id="formDelete{{ $item->id }}" action="{{ route('kelas.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data kelas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('kelas.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Tambah Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-buildings-fill"></i></span>
            <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary">Reset</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Kelas -->
@foreach ($kelas as $item)
<div class="modal fade" id="modalEditKelas{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditKelasLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('kelas.update', $item->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i> Edit Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-buildings-fill"></i></span>
            <input type="text" name="nama_kelas" class="form-control" value="{{ $item->nama_kelas }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary">Reset</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach
@endsection

@section('js')
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session('error') }}',
        showConfirmButton: true
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const kelasId = this.dataset.id;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data kelas yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formDelete' + kelasId).submit();
                    }
                });
            });
        });
    });
</script>
@endsection
