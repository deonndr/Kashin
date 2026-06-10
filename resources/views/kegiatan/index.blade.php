<x-app-layout>
    <x-slot name="title">Kegiatan & Pengeluaran</x-slot>

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
                            if ($pct <= 33) {
                                $c = 'danger';
                            } elseif ($pct <= 66) {
                                $c = 'warning';
                            } else {
                                $c = 'success';
                            }
                        @endphp
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="text-light fw-semibold border-secondary-subtle">{{ $k->nama_kegiatan }}</td>
                            <td class="border-secondary-subtle">Rp {{ number_format($k->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="text-danger fw-bold border-secondary-subtle">
                                -Rp {{ number_format($realisasi, 0, ',', '.') }}
                            </td>
                            <td class="border-secondary-subtle" style="min-width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $c }}" role="progressbar" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-muted small" style="min-width: 30px;">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="border-secondary-subtle">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kegiatan.show', $k) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                    <form method="POST" action="{{ route('kegiatan.destroy', $k) }}"
                                          class="delete-form" data-message="Hapus kegiatan {{ $k->nama_kegiatan }}?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-trash"></i> Hapus
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
</x-app-layout>
