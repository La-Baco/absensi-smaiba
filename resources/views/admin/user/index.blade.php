@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Data User</h3>
            <p class="text-subtitle text-muted">Halaman informasi lengkap User</p>
        </div>
    </div>
</div>
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar User</h4>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser"> Tambah User </button>
                </div>
                <div class="card-content">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-bold-500 text-center">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">{{ ucfirst($user->role) }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form id="formDelete{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $user->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

 <!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i> Tambah User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Nama" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                <select name="role" class="form-select" required>
                  <option value="">-- Pilih Role --</option>
                  <option value="admin">Admin</option>
                  <option value="operator">Operator</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group position-relative">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" id="addPassword" class="form-control" placeholder="Password" required>
                <span class="input-group-text toggle-password" data-target="addPassword" style="cursor: pointer;">
                  <i class="bi bi-eye-slash"></i>
                </span>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary"> Reset</button>
            <button type="submit" class="btn btn-primary"> Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>


@foreach ($users as $user)
<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-labelledby="modalEditUserLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i> Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                <select name="role" class="form-select" required>
                  <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group position-relative">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" id="editPassword{{ $user->id }}" class="form-control" placeholder="Password (opsional)">
                <span class="input-group-text toggle-password" data-target="editPassword{{ $user->id }}" style="cursor: pointer;">
                  <i class="bi bi-eye-slash"></i>
                </span>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary"></i> Reset</button>
            <button type="submit" class="btn btn-primary"></i> Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </div>
  </div>


@endforeach

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

{{-- Script untuk konfirmasi hapus --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'User yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formDelete' + userId).submit();
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(el => {
            el.addEventListener('click', function () {
                const passwordId = this.dataset.target;
                const passwordInput = document.getElementById(passwordId);
                const confirmInput = passwordInput
                    .closest('.modal-body')
                    .querySelector('input[name="password_confirmation"]');

                const icon = this.querySelector('i');
                const isHidden = passwordInput.getAttribute('type') === 'password';

                // Ganti tipe input
                passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                if (confirmInput) {
                    confirmInput.setAttribute('type', isHidden ? 'text' : 'password');
                }

                // Ganti ikon
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        });
    });
</script>



@endsection


@endsection




