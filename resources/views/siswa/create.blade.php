<x-app-layout>
    <x-slot name="title">Tambah Siswa</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Tambah Siswa</h2>
            <p class="text-muted small mb-0">Daftarkan siswa baru ke dalam sistem</p>
        </div>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card kas-card" style="max-width: 540px;">
        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nisn">NISN</label>
                <input type="text" id="nisn" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                       value="{{ old('nisn') }}" placeholder="10 digit NISN" maxlength="10">
                @error('nisn') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nama_siswa">Nama Lengkap</label>
                <input type="text" id="nama_siswa" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror"
                       value="{{ old('nama_siswa') }}" placeholder="Nama lengkap siswa">
                @error('nama_siswa') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="kelas">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror"
                       value="{{ old('kelas') }}" placeholder="Contoh: XI RPL">
                @error('kelas') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="email">Email Akun Login</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="nama@siswa.dev">
                @error('email') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
                <div class="form-text text-muted small mt-1">
                    <i class="bi bi-info-circle me-1"></i>
                    Akun login akan otomatis dibuat. Password default: <code>siswa123</code>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Siswa</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>

