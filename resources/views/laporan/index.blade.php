<x-app-layout>
    <x-slot name="title">Laporan Keuangan</x-slot>

    <div class="page-header">
        <div>
            <h2 class="font-display text-white mb-0">Laporan Keuangan</h2>
            <p class="font-caption mb-0">Rekap lengkap arus kas masuk & keluar kelas</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Total Pemasukan</div>
                <h3 class="font-heading text-success mb-0">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Total Pengeluaran</div>
                <h3 class="font-heading text-danger mb-0">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Saldo Bersih</div>
                <h3 class="font-heading {{ $saldo >= 0 ? 'text-success' : 'text-danger' }} mb-2">
                    {{ $saldo < 0 ? '-' : '' }}Rp {{ number_format(abs($saldo), 0, ',', '.') }}
                </h3>
                <div>
                    <span class="kas-badge {{ $saldo >= 0 ? 'kas-badge-success' : 'kas-badge-danger' }} py-1 px-2" style="font-size: 11px !important;">
                        {{ $saldo >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Rekap Per Periode --}}
        <div class="col-lg-6">
            <div class="card kas-card h-100">
                <h5 class="font-heading text-white mb-3">Rekap Per Periode Iuran</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="border-secondary-subtle">Periode</th>
                                <th class="border-secondary-subtle">Lunas</th>
                                <th class="border-secondary-subtle">Belum</th>
                                <th class="border-secondary-subtle">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periodes as $p)
                                <tr>
                                    <td class="text-light fw-semibold border-secondary-subtle">{{ $p['nama'] }}</td>
                                    <td class="border-secondary-subtle">
                                        <span class="kas-badge kas-badge-success py-1 px-2" style="font-size: 11px !important;">{{ $p['lunas'] }}</span>
                                    </td>
                                    <td class="border-secondary-subtle">
                                        <span class="kas-badge kas-badge-warning py-1 px-2" style="font-size: 11px !important;">{{ $p['belum'] }}</span>
                                    </td>
                                    <td class="text-success fw-bold border-secondary-subtle">
                                        Rp {{ number_format($p['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 font-caption border-secondary-subtle">
                                        Belum ada periode iuran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Rekap Per Kegiatan --}}
        <div class="col-lg-6">
            <div class="card kas-card h-100">
                <h5 class="font-heading text-white mb-3">Rekap Per Kegiatan</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="border-secondary-subtle">Kegiatan</th>
                                <th class="border-secondary-subtle">Estimasi</th>
                                <th class="border-secondary-subtle">Realisasi</th>
                                <th class="border-secondary-subtle">Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kegiatans as $k)
                                <tr>
                                    <td class="text-light fw-semibold border-secondary-subtle">{{ $k['nama'] }}</td>
                                    <td class="border-secondary-subtle">Rp {{ number_format($k['estimasi'], 0, ',', '.') }}</td>
                                    <td class="text-danger border-secondary-subtle">Rp {{ number_format($k['realisasi'], 0, ',', '.') }}</td>
                                    <td class="{{ $k['sisa'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold border-secondary-subtle">
                                        {{ $k['sisa'] < 0 ? '-' : '' }}Rp {{ number_format(abs($k['sisa']), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 font-caption border-secondary-subtle">
                                        Belum ada data kegiatan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
