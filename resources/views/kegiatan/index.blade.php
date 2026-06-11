<x-app-layout>
    <x-slot name="title">Kegiatan & Pengeluaran</x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Kegiatan & Pengeluaran</h2>
            <p class="text-muted small mb-0">Kelola agenda kegiatan kelas dan realisasi anggarannya</p>
        </div>
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-calendar-plus"></i> Tambah Kegiatan
        </a>
    </div>

    <div class="card kas-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nama Kegiatan</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Estimasi Biaya</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Realisasi</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Progress</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatans as $i => $k)
                        @php
                            $realisasi = $k->pengeluaran_sum_nominal_keluar ?? 0;
                            $pct = $k->estimasi_biaya > 0
                                ? min(100, round($realisasi / $k->estimasi_biaya * 100))
                                : 0;
                            $barColor = $pct <= 33 ? '#ff2f55' : ($pct <= 66 ? '#C49A3C' : '#22c55e');
                        @endphp
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="text-light fw-semibold border-secondary-subtle">{{ $k->nama_kegiatan }}</td>
                            <td class="text-muted border-secondary-subtle">Rp {{ number_format($k->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="border-secondary-subtle fw-semibold" style="color:var(--kd-text-secondary);">
                                −Rp {{ number_format($realisasi, 0, ',', '.') }}
                            </td>
                            <td class="border-secondary-subtle" style="min-width: 160px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="flex:1; height:5px; background:var(--kd-bg-elevated); border-radius:99px; overflow:hidden;">
                                        <div style="width:{{ $pct }}%; height:100%; background:{{ $barColor }}; border-radius:99px;"></div>
                                    </div>
                                    <span class="text-muted small" style="min-width:30px; font-size:11px; font-weight:600;">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="border-secondary-subtle">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kegiatan.show', $k) }}" class="ks-btn-icon" title="Detail">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                    <form method="POST" action="{{ route('kegiatan.destroy', $k) }}"
                                          class="delete-form" data-message="Hapus kegiatan {{ $k->nama_kegiatan }}?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ks-btn-icon ks-btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted border-secondary-subtle">
                                <i class="bi bi-clipboard-data fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold">Belum ada kegiatan</div>
                                <small>Klik "Tambah Kegiatan" untuk mencatat agenda kelas baru.</small>
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
