@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('css')
<style>
    .id-card {
        width: 570px;
        height: 350px;
        border-radius: 15px;
        overflow: hidden;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .photo-placeholder {
        width: 80px;
        height: 100px;
        font-size: 12px;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
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
                            <div class="h5 fw-bold mb-0 text-white">KARTU IDENTITAS</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="photo-placeholder border border-white d-flex align-items-center justify-content-center text-white text-opacity-50" style="border-style: dashed;">@if ($siswa->foto)
                                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto" class="img-fluid" style="width: 80px; height: 100px; object-fit: cover;">
                            @else
                                FOTO
                            @endif</div>
                            <div class="text-end small">
                                <div class="fw-bold">EXP:</div>
                                <div class="fw-light">12/2028</div>
                            </div>
                        </div>
                    </div>

                    <div class="row gx-3 mb-2">
                        <div class="col-6">
                            <div class="small text-white-50">NAMA</div>
                            <div class="border-bottom border-white-50 py-1" id="namaDisplay">{{ $siswa->nama }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-white-50">UID</div>
                            <div class="border-bottom border-white-50 py-1">{{ $siswa->uid }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-white-50">KELAS</div>
                            <div class="border-bottom border-white-50 py-1" id="kelasDisplay">{{ $siswa->kelas->nama_kelas ?? '[KELAS]' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-white-50">NIS</div>
                            <div class="border-bottom border-white-50 py-1" id="nisDisplay">{{ $siswa->nis }}</div>
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
            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('PUT')
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Data Siswa</h5>
                    </div>
                    <div class="card-body row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Kelas</label>
                            <select name="id_kelas" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}" {{ $siswa->id_kelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Foto (Opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Status Aktif</label>
                            <select name="aktif" class="form-select" required>
                                <option value="1" {{ $siswa->aktif ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$siswa->aktif ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
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
    document.addEventListener('DOMContentLoaded', function () {
        const namaInput = document.querySelector('input[name="nama"]');
        const nisInput = document.querySelector('input[name="nis"]');
        const kelasSelect = document.querySelector('select[name="id_kelas"]');

        const namaDisplay = document.getElementById('namaDisplay');
        const kelasDisplay = document.getElementById('kelasDisplay');
        const nisDisplay = document.getElementById('nisDisplay');

        // Nama
        namaInput.addEventListener('input', function () {
            namaDisplay.textContent = this.value || '[NAMA LENGKAP]';
        });

        // Kelas
        kelasSelect.addEventListener('change', function () {
            const selectedText = this.options[this.selectedIndex].text;
            kelasDisplay.textContent = selectedText || '[KELAS]';
        });

        // NIS
        nisInput.addEventListener('input', function () {
            nisDisplay.textContent = this.value || '[NIS]';
        });
    });
</script>
@endsection
