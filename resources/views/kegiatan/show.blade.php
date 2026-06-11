<x-app-layout>
    <x-slot name="title">Detail Kegiatan</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-muted small mb-0">Detail kegiatan & rincian pengeluaran</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pengeluaran.create', $kegiatan) }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
                <i class="bi bi-plus-circle"></i> Tambah Pengeluaran
            </a>
            <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-2">Estimasi Biaya</div>
                <h4 class="fw-bold text-white mb-0">Rp {{ number_format($kegiatan->estimasi_biaya, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-2">Total Realisasi</div>
                <h4 class="fw-bold text-danger mb-0">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            @php 
                $sisa = $kegiatan->estimasi_biaya - $totalRealisasi;
                if ($pct <= 33) {
                    $c = 'danger';
                } elseif ($pct <= 66) {
                    $c = 'warning';
                } else {
                    $c = 'success';
                }
            @endphp
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Sisa Anggaran</div>
                <h4 class="font-heading {{ $sisa >= 0 ? 'text-success' : 'text-danger' }} mb-2">
                    {{ $sisa < 0 ? '-' : '' }}Rp {{ number_format(abs($sisa), 0, ',', '.') }}
                    @if($sisa < 0)<small class="text-muted fs-6 fw-normal">(Melebihi)</small>@endif
                </h4>
                <div class="progress mb-1" style="height: 6px;">
                    <div class="progress-bar bg-{{ $c }}" 
                         role="progressbar" style="width: {{ $pct }}%"></div>
                </div>
                <small class="font-caption">{{ $pct }}% anggaran terpakai</small>
            </div>
        </div>
    </div>

    {{-- Pengeluaran Table --}}
    <div class="card kas-card">
        <div class="card-section-header">
            <h5 class="font-heading text-white mb-0">Rincian Pengeluaran</h5>
            <span class="kas-badge kas-badge-secondary py-1 px-2">
                {{ $kegiatan->pengeluaran->count() }} item
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nama Item</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nominal</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Tanggal</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan->pengeluaran as $i => $p)
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="text-light fw-semibold border-secondary-subtle">{{ $p->nama_pengeluaran }}</td>
                            <td class="text-danger fw-bold border-secondary-subtle">
                                -Rp {{ number_format($p->nominal_keluar, 0, ',', '.') }}
                            </td>
                            <td class="text-muted small border-secondary-subtle">
                                {{ $p->tanggal_keluar ? \Carbon\Carbon::parse($p->tanggal_keluar)->format('d M Y') : '-' }}
                            </td>
                            <td class="border-secondary-subtle">
                                <form method="POST" action="{{ route('pengeluaran.destroy', $p) }}"
                                      class="delete-form" data-message="Hapus item pengeluaran ini?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ks-btn-icon ks-btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted border-secondary-subtle">
                                <i class="bi bi-box-seam fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold">Belum ada pengeluaran</div>
                                <small>Klik "Tambah Pengeluaran" untuk mencatat rincian biaya.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<style>
.ks-btn-icon {
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 8px;
    color: var(--kd-text-muted);
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.ks-btn-icon:hover { border-color: var(--kd-border-strong); color: var(--kd-text-primary); }
.ks-btn-danger:hover { border-color: var(--kd-danger) !important; color: var(--kd-danger) !important; background: var(--kd-danger-dim) !important; }
</style>
</x-app-layout>
