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
                                <div class="d-flex gap-2 align-items-center">
                                    {{-- Tombol Edit: selalu bisa --}}
                                    <a href="{{ route('periode-iuran.edit', $p) }}"
                                       class="ks-btn-icon" title="Edit" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if($p->pembayaran_iurans_count > 0)
                                        {{-- Tidak bisa dihapus: masih ada transaksi --}}
                                        <button type="button"
                                                class="ks-btn-icon"
                                                disabled
                                                title="Tidak bisa dihapus: masih ada {{ $p->pembayaran_iurans_count }} transaksi di periode ini"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        {{-- Aman dihapus: tidak ada transaksi --}}
                                        <form method="POST" action="{{ route('periode-iuran.destroy', $p) }}"
                                              class="delete-form"
                                              data-message="Hapus periode {{ $p->nama_periode }}? Periode ini tidak memiliki transaksi sehingga aman dihapus.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ks-btn-icon ks-btn-danger" title="Hapus" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
.ks-btn-icon:disabled { opacity: 0.4; cursor: not-allowed; }
.ks-btn-danger:hover { border-color: var(--kd-danger) !important; color: var(--kd-danger) !important; background: var(--kd-danger-dim) !important; }
</style>
</x-app-layout>
