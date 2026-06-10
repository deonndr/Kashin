<x-app-layout>
    <x-slot name="title">Tambah Pengeluaran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Tambah Pengeluaran</h2>
            <p class="text-muted small mb-0">
                Untuk kegiatan: <strong class="text-light">{{ $kegiatan->nama_kegiatan }}</strong>
            </p>
        </div>
        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card kas-card" style="max-width: 540px;">
        <form method="POST" action="{{ route('pengeluaran.store', $kegiatan) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nama_pengeluaran">Nama Item</label>
                <input type="text" id="nama_pengeluaran" name="nama_pengeluaran"
                       class="form-control @error('nama_pengeluaran') is-invalid @enderror"
                       value="{{ old('nama_pengeluaran') }}" placeholder="Contoh: Sewa Bus, Pembelian ATK">
                @error('nama_pengeluaran')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nominal_keluar">Nominal (Rp)</label>
                <input type="number" id="nominal_keluar" name="nominal_keluar"
                       class="form-control @error('nominal_keluar') is-invalid @enderror"
                       value="{{ old('nominal_keluar') }}" placeholder="120000" min="1">
                @error('nominal_keluar')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="tanggal_keluar">Tanggal <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="date" id="tanggal_keluar" name="tanggal_keluar"
                       class="form-control @error('tanggal_keluar') is-invalid @enderror"
                       value="{{ old('tanggal_keluar', date('Y-m-d')) }}">
                @error('tanggal_keluar')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Pengeluaran</button>
                <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
