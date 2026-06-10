<x-app-layout>
    <x-slot name="title">Tambah Kegiatan</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Tambah Kegiatan</h2>
            <p class="text-muted small mb-0">Buat agenda kegiatan baru</p>
        </div>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card kas-card" style="max-width: 540px;">
        <form method="POST" action="{{ route('kegiatan.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nama_kegiatan">Nama Kegiatan</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                       value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Study Tour Bandung">
                @error('nama_kegiatan') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="estimasi_biaya">Estimasi Biaya (Rp)</label>
                <input type="number" id="estimasi_biaya" name="estimasi_biaya" class="form-control @error('estimasi_biaya') is-invalid @enderror"
                       value="{{ old('estimasi_biaya') }}" placeholder="1500000" min="0">
                @error('estimasi_biaya') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Kegiatan</button>
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
