@extends('layouts.app')

@section('title', 'Edit Guru')

@section('css')
    <style>
        .id-card {
            width: 570px;
            height: 350px;
            border-radius: 15px;
            overflow: hidden;
            background: linear-gradient(135deg, #005c97 0%, #363795 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .photo-placeholder {
            width: 80px;
            height: 100px;
            font-size: 12px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-md-8 mx-auto">
                {{-- Kartu Identitas --}}
                <div class="id-card mx-auto shadow mb-4">
                    <div class="p-4 h-100 d-flex flex-column justify-content-between text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="small">SMA Islam Baiturrahman</div>
                                <div class="h5 fw-bold mb-0 text-white">KARTU IDENTITAS GURU</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="photo-placeholder border border-white d-flex align-items-center justify-content-center text-white text-opacity-50"
                                    style="border-style: dashed;">
                                    @if ($guru->foto)
                                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto" class="img-fluid"
                                            style="width: 80px; height: 100px; object-fit: cover;">
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
                                <div class="border-bottom border-white-50 py-1" id="namaDisplay">{{ $guru->nama }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">UID</div>
                                <div class="border-bottom border-white-50 py-1">{{ $guru->uid }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">NIP</div>
                                <div class="border-bottom border-white-50 py-1" id="nipDisplay">{{ $guru->nip }}</div>
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

                {{-- Form Edit --}}
                <form action="{{ route('guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data"
                    class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Data Guru</h5>
                        </div>

                        <div class="card-body row">
                            <input type="hidden" name="uid" value="{{ old('uid', $guru->uid) }}">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control"
                                    value="{{ old('nip', $guru->nip) }}" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $guru->nama) }}" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir', $guru->tempat_lahir) }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}">
                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Foto (Opsional)</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                @if ($guru->foto)
                                    <small class="text-muted">Foto saat ini: <a
                                            href="{{ asset('storage/' . $guru->foto) }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Status Aktif</label>
                                <select name="aktif" class="form-select" required>
                                    <option value="1" {{ old('aktif', $guru->aktif) == 1 ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ old('aktif', $guru->aktif) == 0 ? 'selected' : '' }}>Tidak
                                        Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.querySelector('input[name="nama"]');
            const nipInput = document.querySelector('input[name="nip"]');

            const namaDisplay = document.getElementById('namaDisplay');
            const nipDisplay = document.getElementById('nipDisplay');

            namaInput.addEventListener('input', function() {
                namaDisplay.textContent = this.value || '[NAMA GURU]';
            });

            nipInput.addEventListener('input', function() {
                nipDisplay.textContent = this.value || '[NIP]';
            });
        });
    </script>
@endsection
