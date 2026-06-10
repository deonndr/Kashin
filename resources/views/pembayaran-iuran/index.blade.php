<x-app-layout>
    <x-slot name="title">Pembayaran Iuran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Pembayaran Iuran</h2>
            <p class="text-muted small mb-0">Rekap seluruh transaksi masuk iuran kelas</p>
        </div>
        <a href="{{ route('pembayaran-iuran.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-cash-coin"></i> Catat Pembayaran
        </a>
    </div>

    {{-- Filter Bar Toolbar --}}
    <div class="kd-filter-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-3 align-items-center w-100">
            <span class="kd-filter-label"><i class="bi bi-filter"></i> Filter:</span>
            <select name="periode" class="form-select" style="width: auto; min-width: 160px;" onchange="this.form.submit()">
                <option value="">Semua Periode</option>
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ request('periode') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_periode }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="form-select" style="width: auto; min-width: 160px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Lunas"       {{ request('status') === 'Lunas'       ? 'selected' : '' }}>Lunas</option>
                <option value="Belum Lunas" {{ request('status') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
            @if(request()->hasAny(['periode','status']))
                <a href="{{ route('pembayaran-iuran.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>

    <div class="card kas-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Siswa</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Periode</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Jumlah Bayar</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Tanggal</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Status</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $i => $p)
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="text-light fw-semibold border-secondary-subtle">{{ $p->siswa->nama_siswa ?? '-' }}</td>
                            <td class="border-secondary-subtle">{{ $p->periodeIuran->nama_periode ?? '-' }}</td>
                            <td class="border-secondary-subtle text-success fw-bold">
                                +Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="text-muted small border-secondary-subtle">
                                {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}
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
                                    <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-trash"></i> Hapus
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
</x-app-layout>
