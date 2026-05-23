@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Account Security</h3>
            <p class="text-subtitle text-muted">Halaman untuk mengubah password akun</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Security</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Change Password</h5>
                </div>
                <div class="card-body">
                    {{-- Tampilkan pesan sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Tampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('operator.password.update') }}" method="POST">
                        @csrf
                        <div class="form-group my-2">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                class="form-control" placeholder="Enter your current password" required>
                        </div>
                        <div class="form-group my-2">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control"
                                placeholder="Enter new password" required minlength="8">
                        </div>
                        <div class="form-group my-2">
                            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control" placeholder="Confirm new password" required minlength="8">
                        </div>

                        <div class="form-group my-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
