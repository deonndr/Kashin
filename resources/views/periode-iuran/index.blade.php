<x-app-layout>
    <x-slot name="title">Periode Iuran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Periode Iuran</h2>
            <p class="text-muted small mb-0">Kelola periode penarikan kas</p>
        </div>
        <a href="{{ route('periode-iuran.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-calendar-plus"></i> Tambah Periode
        </a>
    </div>

    <div class="card kas-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nama Periode</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nominal Tagihan</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Total Bayar</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $i => $p)
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="text-light fw-semibold border-secondary-subtle">{{ $p->nama_periode }}</td>
                            <td class="border-secondary-subtle">Rp {{ number_format($p->nominal_tagihan, 0, ',', '.') }}</td>
                            <td class="border-secondary-subtle">
                                <span class="kas-badge kas-badge-primary py-1 px-2" style="font-size: 11px !important;">
                                    {{ $p->pembayaran_iurans_count }} transaksi
                                </span>
                            </td>
                            <td class="border-secondary-subtle">
                                <form method="POST" action="{{ route('periode-iuran.destroy', $p) }}"
                                      class="delete-form" data-message="Hapus periode {{ $p->nama_periode }}?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted border-secondary-subtle">
                                <i class="bi bi-calendar3 fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold">Belum ada periode iuran</div>
                                <small>Klik "Tambah Periode" untuk membuat periode penarikan kas baru.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
