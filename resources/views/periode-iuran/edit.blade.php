<x-app-layout>
    <x-slot name="title">Edit Periode Iuran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Edit Periode Iuran</h2>
            <p class="text-muted small mb-0">Ubah nominal tagihan periode</p>
        </div>
        <a href="{{ route('periode-iuran.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Context banner: nama periode tidak bisa diubah --}}
    <div class="kpe-context-banner" style="border-left-color: var(--kd-accent);">
        <div class="kpe-context-icon" style="background: var(--kd-accent-dim); border-color: var(--kd-accent); color: var(--kd-accent);">
            <i class="bi bi-calendar3-fill"></i>
        </div>
        <div>
            <div class="kpe-context-label">Periode</div>
            <div class="kpe-context-name">{{ $periodeIuran->nama_periode }}</div>
        </div>
        <div class="ms-auto">
            <span class="kas-badge kas-badge-secondary py-1 px-3">
                <i class="bi bi-lock-fill me-1"></i> Nama periode tidak dapat diubah
            </span>
        </div>
    </div>

    <div class="card kas-card" style="max-width: 480px;">
        <form method="POST" action="{{ route('periode-iuran.update', $periodeIuran) }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nominal_tagihan">
                    Nominal Tagihan (Rp)
                </label>
                <div class="input-group">
                    <span class="input-group-text text-muted fw-semibold" style="background: var(--kd-bg-elevated); border-color: var(--kd-border);">Rp</span>
                    <input type="number" id="nominal_tagihan" name="nominal_tagihan"
                           class="form-control @error('nominal_tagihan') is-invalid @enderror"
                           value="{{ old('nominal_tagihan', $periodeIuran->nominal_tagihan) }}"
                           placeholder="50000" min="1000">
                    @error('nominal_tagihan')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-muted small mt-1">
                    Nominal saat ini: <strong class="text-light">Rp {{ number_format($periodeIuran->nominal_tagihan, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('periode-iuran.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

<style>
/* reuse dari pengeluaran create */
.kpe-context-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-left: 4px solid var(--kd-accent);
    border-radius: 12px;
    padding: 16px 20px;
    flex-wrap: wrap;
}
.kpe-context-icon {
    width: 42px; height: 42px;
    border: 1.5px solid;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.kpe-context-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--kd-text-muted); margin-bottom: 2px;
}
.kpe-context-name {
    font-size: 15px; font-weight: 700;
    color: var(--kd-text-primary);
}
</style>
</x-app-layout>
