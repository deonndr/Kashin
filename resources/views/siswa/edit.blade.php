<x-app-layout>
    <x-slot name="title">Edit Siswa</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Edit Siswa</h2>
            <p class="text-muted small mb-0">Perbarui data {{ $siswa->nama_siswa }}</p>
        </div>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card kas-card" style="max-width: 540px;">
        <form method="POST" action="{{ route('siswa.update', $siswa) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nisn">NISN</label>
                <input type="text" id="nisn" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                       value="{{ old('nisn', $siswa->nisn) }}" maxlength="10">
                @error('nisn') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nama_siswa">Nama Lengkap</label>
                <input type="text" id="nama_siswa" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror"
                       value="{{ old('nama_siswa', $siswa->nama_siswa) }}">
                @error('nama_siswa') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="kelas">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror"
                       value="{{ old('kelas', $siswa->kelas) }}" placeholder="XI RPL">
                @error('kelas') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Perubahan</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
