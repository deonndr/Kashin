<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- ═══ SALDO HEADER ═══ --}}
    <div class="card kas-card">
        <div class="font-caption text-uppercase mb-1">
            {{ auth()->user()->name }}
        </div>
        <h1 class="font-display text-white mb-3">
            Rp {{ number_format($saldoBersih, 0, ',', '.') }}
        </h1>
        <div class="d-flex flex-wrap gap-2">
            <span class="kas-badge kas-badge-success">
                <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: var(--kd-accent);"></span>
                <span>Masuk: <strong class="ms-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</strong></span>
            </span>
            <span class="kas-badge kas-badge-danger">
                <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: var(--kd-danger);"></span>
                <span>Keluar: <strong class="ms-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</strong></span>
            </span>
            <span class="kas-badge kas-badge-secondary">
                <i class="bi bi-calendar3"></i> 
                <span>{{ $now->translatedFormat('F Y') }}</span>
            </span>
        </div>
    </div>

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="row g-3">
        {{-- Sudah Lunas --}}
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Sudah Lunas</div>
                <h2 class="font-heading text-success mb-1">
                    {{ $sudahLunasIds }} <span class="text-muted fs-6 fw-normal">/ {{ $totalSiswa }} siswa</span>
                </h2>
                @if($sudahLunasIds == 0)
                    <div class="kd-empty-microcopy mb-0">
                        Belum ada pembayaran bulan ini. <a href="{{ route('pembayaran-iuran.create') }}" class="kd-empty-link">Catat Pembayaran →</a>
                    </div>
                @else
                    <p class="font-caption mb-3">
                        {{ $totalSiswa > 0 ? round($sudahLunasIds / $totalSiswa * 100) : 0 }}% siswa lunas bulan ini
                    </p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $totalSiswa > 0 ? round($sudahLunasIds / $totalSiswa * 100) : 0 }}%;" 
                             aria-valuenow="{{ $sudahLunasIds }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $totalSiswa }}"></div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Belum Bayar --}}
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Belum Bayar</div>
                <h2 class="font-heading text-danger mb-1">{{ $belumBayarCount }}</h2>
                <p class="text-danger font-caption mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Perlu ditagih pembayaran bulan ini
                </p>
            </div>
        </div>

        {{-- Kegiatan Aktif --}}
        <div class="col-md-4">
            <div class="card kas-card h-100">
                <div class="font-caption text-uppercase mb-2">Kegiatan Aktif</div>
                <h2 class="font-heading text-primary mb-1">{{ $kegiatanAktif }}</h2>
                <p class="font-caption mb-0">
                    Agenda berjalan di bulan {{ $now->translatedFormat('F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ═══ TRANSAKSI + BOTTOM SECTION ═══ --}}
    <div class="row g-4">
        {{-- Kolom Kiri: Transaksi & Kegiatan --}}
        <div class="col-lg-8 d-flex flex-column" style="gap: 28px;">
            
            {{-- Transaksi Terakhir --}}
            <div class="card kas-card">
                <div class="card-section-header">
                    <h5 class="font-heading text-white mb-0">Transaksi Terakhir</h5>
                    <a href="{{ route('pembayaran-iuran.index') }}" class="text-primary text-decoration-none font-caption fw-semibold">Lihat semua <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($transaksiTerakhir as $txn)
                        <div class="list-group-item bg-transparent border-secondary-subtle px-0 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded d-flex align-items-center justify-content-center fw-bold fs-5 {{ $txn['type'] === 'in' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="width: 40px; height: 40px;">
                                    {{ $txn['type'] === 'in' ? strtoupper(substr($txn['label'], 0, 1)) : '↑' }}
                                </div>
                                <div>
                                    <div class="font-body text-light fw-semibold">{{ $txn['label'] }}</div>
                                    <div class="font-caption">{{ $txn['sub'] }}</div>
                                </div>
                            </div>
                            <div class="font-body fw-bold {{ $txn['type'] === 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $txn['type'] === 'in' ? '+' : '-' }}Rp {{ number_format($txn['amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 font-caption">
                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                            Belum ada transaksi tercatat
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kegiatan & Dana --}}
            <div class="card kas-card">
                <div class="card-section-header">
                    <h5 class="font-heading text-white mb-0">Kegiatan & Dana</h5>
                    <a href="{{ route('kegiatan.index') }}" class="text-primary text-decoration-none font-caption fw-semibold">Lihat semua <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="row g-3">
                    @forelse($kegiatanList as $i => $k)
                        @php
                            $pct = $k['pct'];
                            if ($pct <= 33) {
                                $c = 'danger';
                            } elseif ($pct <= 66) {
                                $c = 'warning';
                            } else {
                                $c = 'success';
                            }
                        @endphp
                        <div class="col-md-6">
                            <div class="kas-card-inner">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-body fw-semibold text-light text-truncate" style="max-width: 70%;">{{ $k['nama'] }}</span>
                                    <span class="kas-badge kas-badge-{{ $c }} py-1 px-2" style="font-size: 11px !important;">{{ $k['pct'] }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $c }}" role="progressbar" style="width: {{ $k['pct'] }}%"></div>
                                </div>
                                <div class="font-caption mt-2">
                                    Rp {{ number_format($k['realisasi'], 0, ',', '.') }} / Rp {{ number_format($k['estimasi'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 font-caption w-100">
                            Belum ada kegiatan terdaftar
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Belum Lunas --}}
        <div class="col-lg-4">
            <div class="card kas-card h-100">
                <div class="card-section-header">
                    <h5 class="font-heading text-white mb-0">Belum Lunas</h5>
                    <a href="{{ route('pembayaran-iuran.index') }}" class="text-primary text-decoration-none font-caption fw-semibold">Lihat semua <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($belumLunasList as $i => $siswa)
                        @php
                            $colors = ['primary', 'info', 'success', 'warning', 'danger'];
                            $c = $colors[$i % count($colors)];
                            $inisial = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $siswa->nama_siswa))));
                            $inisial = substr($inisial, 0, 2);
                        @endphp
                        <div class="list-group-item bg-transparent border-secondary-subtle px-0 py-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small bg-{{ $c }}-subtle text-{{ $c }}" style="width: 34px; height: 34px; font-size: 12px;">
                                    {{ $inisial }}
                                </div>
                                <span class="text-light fw-medium small text-truncate" style="max-width: 140px;">{{ $siswa->nama_siswa }}</span>
                            </div>
                            <span class="kas-badge kas-badge-warning py-1 px-2" style="font-size: 11px !important;">Menunggak</span>
                        </div>
                    @empty
                        <div class="text-center py-5 font-caption">
                            <i class="bi bi-check-circle-fill text-success fs-2 mb-2 d-block"></i>
                            Semua siswa sudah lunas!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
