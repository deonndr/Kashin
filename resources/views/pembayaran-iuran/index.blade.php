<x-app-layout>
    <x-slot name="title">Pembayaran Iuran</x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Pembayaran Iuran</h2>
            <p class="text-muted small mb-0">Rekap seluruh transaksi masuk iuran kelas</p>
        </div>
        <a href="{{ route('pembayaran-iuran.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-cash-coin"></i> Catat Pembayaran
        </a>
    </div>
    
    {{-- ── Filter Pill Bar ── --}}
    <div class="kpf-bar">
        <span class="kpf-label"><i class="bi bi-funnel-fill me-1"></i> Filter</span>

        {{-- Periode pills --}}
        <div class="kpf-group">
            <a href="{{ route('pembayaran-iuran.index', array_merge(request()->except('periode'), ['status' => request('status')])) }}"
               class="kpf-pill {{ !request('periode') ? 'active' : '' }}">
                Semua Periode
            </a>
            @foreach($periodes as $p)
                <a href="{{ route('pembayaran-iuran.index', array_merge(request()->all(), ['periode' => $p->id])) }}"
                   class="kpf-pill {{ request('periode') == $p->id ? 'active' : '' }}">
                    {{ $p->nama_periode }}
                </a>
            @endforeach
        </div>

        <div class="kpf-divider"></div>

        {{-- Status pills --}}
        <div class="kpf-group">
            <a href="{{ route('pembayaran-iuran.index', array_merge(request()->except('status'), ['periode' => request('periode')])) }}"
               class="kpf-pill {{ !request('status') ? 'active' : '' }}">
                Semua Status
            </a>
            <a href="{{ route('pembayaran-iuran.index', array_merge(request()->all(), ['status' => 'Lunas'])) }}"
               class="kpf-pill kpf-pill--success {{ request('status') === 'Lunas' ? 'active' : '' }}">
                <span class="kpf-dot kpf-dot--ok"></span> Lunas
            </a>
            <a href="{{ route('pembayaran-iuran.index', array_merge(request()->all(), ['status' => 'Belum Lunas'])) }}"
               class="kpf-pill kpf-pill--warn {{ request('status') === 'Belum Lunas' ? 'active' : '' }}">
                <span class="kpf-dot kpf-dot--warn"></span> Belum Lunas
            </a>
        </div>

        @if(request()->hasAny(['periode','status']))
            <a href="{{ route('pembayaran-iuran.index') }}" class="kpf-reset">
                <i class="bi bi-x-circle me-1"></i> Reset
            </a>
        @endif

        {{-- Count badge --}}
        <span class="kpf-count ms-auto">{{ $pembayarans->count() }} data</span>
    </div>

    {{-- ── Table ── --}}
    <div class="card kas-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Siswa</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Periode</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Jumlah</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Tanggal</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Status</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $i => $p)
                        @php
                            $kata = explode(' ', $p->siswa->nama_siswa ?? '?');
                            $inisial = strtoupper(substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : ''));
                            $palette = ['#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#8b5cf6'];
                            $color   = $p->siswa ? $palette[$p->siswa->id % count($palette)] : '#9E9488';
                        @endphp
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="border-secondary-subtle">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold"
                                         style="width:30px;height:30px;font-size:11px;background:{{ $color }}18;color:{{ $color }};border:1.5px solid {{ $color }}35;flex-shrink:0;">
                                        {{ $inisial }}
                                    </div>
                                    <span class="text-light fw-semibold">{{ $p->siswa->nama_siswa ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-muted border-secondary-subtle small">{{ $p->periodeIuran->nama_periode ?? '-' }}</td>
                            <td class="border-secondary-subtle text-success fw-bold">
                                +Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="text-muted small border-secondary-subtle">
                                {{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d M Y') }}
                            </td>
                            <td class="border-secondary-subtle">
                                @if($p->status_bayar === 'Lunas')
                                    <span class="kas-badge kas-badge-success py-1 px-2" style="font-size: 11px !important;">Lunas</span>
                                @else
                                    <span class="kas-badge kas-badge-warning py-1 px-2" style="font-size: 11px !important;">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="border-secondary-subtle">
                                <form method="POST" action="{{ route('pembayaran-iuran.destroy', $p) }}"
                                      class="delete-form" data-message="Hapus data pembayaran ini?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ks-btn-icon ks-btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted border-secondary-subtle">
                                <i class="bi bi-wallet2 fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold">Belum ada data pembayaran</div>
                                <small>Data pembayaran yang dicatat akan muncul di sini.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<style>
/* ── Filter Pill Bar ── */
.kpf-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 12px;
    padding: 10px 16px;
}
.kpf-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--kd-text-muted);
    flex-shrink: 0;
    margin-right: 4px;
}
.kpf-group { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.kpf-divider {
    width: 1px; height: 22px;
    background: var(--kd-border-strong);
    flex-shrink: 0;
    margin: 0 4px;
}

/* Pills */
.kpf-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    border: 1.5px solid var(--kd-border);
    background: var(--kd-bg-elevated);
    color: var(--kd-text-muted);
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all .15s;
    white-space: nowrap;
}
.kpf-pill:hover {
    border-color: var(--kd-border-strong);
    color: var(--kd-text-primary);
}
.kpf-pill.active {
    background: var(--kd-accent-dim);
    border-color: var(--kd-accent);
    color: var(--kd-accent);
}
.kpf-pill--success.active {
    background: #22c55e18;
    border-color: #22c55e;
    color: #22c55e;
}
.kpf-pill--warn.active {
    background: var(--kd-warning-dim);
    border-color: var(--kd-warning);
    color: var(--kd-warning);
}
.kpf-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.kpf-dot--ok   { background: #22c55e; }
.kpf-dot--warn { background: var(--kd-warning); }

.kpf-reset {
    font-size: 11px; font-weight: 600;
    color: var(--kd-text-muted);
    text-decoration: none;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid var(--kd-border);
    transition: all .15s;
}
.kpf-reset:hover { color: var(--kd-danger); border-color: var(--kd-danger); background: var(--kd-danger-dim); }

.kpf-count {
    font-size: 11px; font-weight: 700;
    color: var(--kd-text-muted);
    letter-spacing: .04em;
}

/* reuse from siswa/index */
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
.ks-btn-danger:hover { border-color: var(--kd-danger) !important; color: var(--kd-danger) !important; background: var(--kd-danger-dim) !important; }
</style>
</x-app-layout>
