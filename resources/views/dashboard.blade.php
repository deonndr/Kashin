<x-app-layout>
    <x-slot name="title">Overview</x-slot>

    {{-- ═══ HERO: Saldo Bersih ═══ --}}
    <div class="db-hero">
        <div class="db-hero__left">
            <div class="db-hero__label">{{ auth()->user()->name }}</div>
            <div class="db-hero__saldo">Rp {{ number_format($saldoBersih, 0, ',', '.') }}</div>
            <div class="db-hero__chips">
                <span class="db-chip db-chip--in">
                    <span class="db-chip__dot" style="background:var(--kd-accent);"></span>
                    Masuk · Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </span>
                <span class="db-chip db-chip--out">
                    <span class="db-chip__dot" style="background:var(--kd-danger);"></span>
                    Keluar · Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </span>
                <span class="db-chip">
                    <i class="bi bi-calendar3 me-1"></i>{{ $now->translatedFormat('F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="db-stat-row">

        {{-- Sudah Lunas --}}
        <div class="db-stat-card">
            <div class="db-stat-card__icon" style="background:var(--kd-bg-elevated); color:var(--kd-text-muted);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="db-stat-card__label">Sudah Lunas</div>
            <div class="db-stat-card__value">
                {{ $sudahLunasIds }}
                <span class="db-stat-card__denom">/ {{ $totalSiswa }}</span>
            </div>
            @if($sudahLunasIds > 0)
                <div class="db-stat-card__bar-wrap">
                    <div class="db-stat-card__bar" style="width:{{ $totalSiswa > 0 ? round($sudahLunasIds/$totalSiswa*100) : 0 }}%; background:var(--kd-border-strong);"></div>
                </div>
                <div class="db-stat-card__sub">{{ $totalSiswa > 0 ? round($sudahLunasIds/$totalSiswa*100) : 0 }}% siswa lunas bulan ini</div>
            @else
                <div class="db-stat-card__sub">
                    Belum ada. <a href="{{ route('pembayaran-iuran.create') }}" style="color:var(--kd-text-secondary);">Catat →</a>
                </div>
            @endif
        </div>

        {{-- Belum Bayar --}}
        <div class="db-stat-card">
            <div class="db-stat-card__icon" style="background:var(--kd-bg-elevated); color:var(--kd-text-muted);">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="db-stat-card__label">Belum Bayar</div>
            <div class="db-stat-card__value">{{ $belumBayarCount }}</div>
            <div class="db-stat-card__sub">Perlu ditagih bulan ini</div>
        </div>

        {{-- Kegiatan Aktif --}}
        <div class="db-stat-card">
            <div class="db-stat-card__icon" style="background:var(--kd-bg-elevated); color:var(--kd-text-muted);">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="db-stat-card__label">Kegiatan Aktif</div>
            <div class="db-stat-card__value">{{ $kegiatanAktif }}</div>
            <div class="db-stat-card__sub">Bulan {{ $now->translatedFormat('F Y') }}</div>
        </div>

    </div>

    {{-- ═══ BOTTOM: Transaksi + Belum Lunas ═══ --}}
    <div class="db-bottom-grid">

        {{-- Kolom Kiri: Transaksi Terakhir --}}
        <div class="db-col-main">
            <div class="db-section-card">
                <div class="db-section-header">
                    <span class="db-section-title">Transaksi Terakhir</span>
                    <a href="{{ route('pembayaran-iuran.index') }}" class="db-section-link">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="db-txn-list">
                    @forelse($transaksiTerakhir as $txn)
                        @php
                            $isIn      = $txn['type'] === 'in';
                            $avatarClr = $isIn ? '#6282ED' : '#ff2f55';
                            $amountClr = $isIn ? '#22c55e' : '#ff2f55';
                            $initial   = $isIn ? strtoupper(substr($txn['label'], 0, 1)) : '↑';
                        @endphp
                        <div class="db-txn-row">
                            <div class="db-txn-avatar" style="background:{{ $avatarClr }}18; color:{{ $avatarClr }}; border-color:{{ $avatarClr }}35;">
                                {{ $initial }}
                            </div>
                            <div class="db-txn-info">
                                <div class="db-txn-name">{{ $txn['label'] }}</div>
                                <div class="db-txn-sub">{{ $txn['sub'] }}</div>
                            </div>
                            <div class="db-txn-amount" style="color:{{ $amountClr }};">
                                {{ $isIn ? '+' : '−' }}Rp {{ number_format($txn['amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="db-empty">
                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                            Belum ada transaksi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Belum Lunas --}}
        <div class="db-col-side">
            <div class="db-section-card">
                <div class="db-section-header">
                    <span class="db-section-title">Belum Lunas</span>
                    <a href="{{ route('pembayaran-iuran.index') }}" class="db-section-link">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="db-siswa-list">
                    @forelse($belumLunasList as $siswa)
                        @php
                            $kata = explode(' ', $siswa->nama_siswa);
                            $inisial = strtoupper(substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : ''));
                            $palette = ['#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#8b5cf6','#10b981','#f97316'];
                            $color   = $palette[$siswa->id % count($palette)];
                        @endphp
                        <div class="db-siswa-row">
                            <div class="db-siswa-avatar" style="background:{{ $color }}18; color:{{ $color }}; border-color:{{ $color }}35;">
                                {{ $inisial }}
                            </div>
                            <span class="db-siswa-name">{{ $siswa->nama_siswa }}</span>
                            <span class="db-belum-badge">Belum</span>
                        </div>
                    @empty
                        <div class="db-empty">
                            <i class="bi bi-check-circle-fill fs-2 mb-2 d-block" style="color:#22c55e;"></i>
                            Semua siswa sudah lunas!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ═══ KEGIATAN & DANA (full-width, berdiri sendiri) ═══ --}}
    <div class="db-section-card">
        <div class="db-section-header">
            <span class="db-section-title">Kegiatan & Dana</span>
            <a href="{{ route('kegiatan.index') }}" class="db-section-link">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="db-kegiatan-grid">
            @forelse($kegiatanList as $k)
                @php
                    $pct = $k['pct'];
                    $barColor = $pct <= 33 ? '#ff2f55' : ($pct <= 66 ? '#C49A3C' : '#22c55e');
                @endphp
                <div class="db-kegiatan-card">
                    <div class="db-kegiatan-top">
                        <span class="db-kegiatan-name">{{ $k['nama'] }}</span>
                        <span class="db-kegiatan-pct" style="color:{{ $barColor }};">{{ $pct }}%</span>
                    </div>
                    <div class="db-bar-wrap">
                        <div class="db-bar-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                    </div>
                    <div class="db-kegiatan-nominal">
                        Rp {{ number_format($k['realisasi'], 0, ',', '.') }}
                        <span style="color:var(--kd-text-muted);">/ Rp {{ number_format($k['estimasi'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="db-empty" style="grid-column:1/-1;">Belum ada kegiatan</div>
            @endforelse
        </div>
    </div>

<style>
/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
.db-hero {
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 16px;
    padding: 28px 32px;
}
.db-hero__label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--kd-text-muted);
    margin-bottom: 8px;
}
.db-hero__saldo {
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    color: var(--kd-text-primary);
    letter-spacing: -.02em;
    margin-bottom: 16px;
    line-height: 1;
}
.db-hero__chips { display: flex; flex-wrap: wrap; gap: 8px; }
.db-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 20px;
    font-size: 12px; font-weight: 600;
    color: var(--kd-text-secondary);
}
.db-chip--in  { border-color: #6282ed35; background: #6282ed10; color: var(--kd-accent); }
.db-chip--out { border-color: #ff2f5535; background: #ff2f5510; color: #ff2f55; }
.db-chip__dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* ══════════════════════════════════════
   STAT ROW
══════════════════════════════════════ */
.db-stat-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
@media (max-width: 768px) { .db-stat-row { grid-template-columns: 1fr; } }

.db-stat-card {
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 14px;
    padding: 20px 22px;
    display: flex; flex-direction: column; gap: 6px;
    transition: border-color .18s, transform .12s;
}
.db-stat-card:hover { border-color: var(--kd-border-strong); transform: translateY(-2px); }

.db-stat-card__icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    margin-bottom: 4px;
}
.db-stat-card__label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--kd-text-muted);
}
.db-stat-card__value {
    font-size: 2.2rem; font-weight: 800;
    letter-spacing: -.02em; line-height: 1;
}
.db-stat-card__denom { font-size: .9rem; font-weight: 500; color: var(--kd-text-muted); }
.db-stat-card__sub { font-size: 12px; color: var(--kd-text-muted); }
.db-stat-card__bar-wrap {
    height: 5px;
    background: var(--kd-bg-elevated);
    border-radius: 99px;
    overflow: hidden;
    margin: 2px 0;
}
.db-stat-card__bar { height: 100%; border-radius: 99px; transition: width .4s ease; }

/* ══════════════════════════════════════
   BOTTOM GRID
══════════════════════════════════════ */
.db-bottom-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 14px;
    align-items: start;
}
.db-col-main { display: flex; flex-direction: column; gap: 14px; }
@media (max-width: 992px) { .db-bottom-grid { grid-template-columns: 1fr; } }

.db-section-card {
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 14px;
    padding: 20px 22px;
}
.db-section-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
}
.db-section-title { font-size: 14px; font-weight: 700; color: var(--kd-text-primary); }
.db-section-link {
    font-size: 12px; font-weight: 600;
    color: var(--kd-text-muted); text-decoration: none;
    transition: color .15s;
}
.db-section-link:hover { color: var(--kd-text-primary); }

/* ── Transaksi ── */
.db-txn-list { display: flex; flex-direction: column; }
.db-txn-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--kd-border);
}
.db-txn-row:last-child { border-bottom: none; }
.db-txn-avatar {
    width: 38px; height: 38px;
    border-radius: 10px; border: 1.5px solid;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; flex-shrink: 0;
}
.db-txn-info { flex: 1; min-width: 0; }
.db-txn-name { font-size: 13px; font-weight: 600; color: var(--kd-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-txn-sub  { font-size: 11px; color: var(--kd-text-muted); }
.db-txn-amount { font-size: 13px; font-weight: 700; flex-shrink: 0; }

/* ── Kegiatan ── */
.db-kegiatan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}
.db-kegiatan-card {
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 12px;
    padding: 14px;
    display: flex; flex-direction: column; gap: 8px;
}
.db-kegiatan-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.db-kegiatan-name { font-size: 13px; font-weight: 600; color: var(--kd-text-primary); line-height: 1.3; }
.db-kegiatan-pct  { font-size: 13px; font-weight: 800; flex-shrink: 0; }
.db-bar-wrap { height: 5px; background: var(--kd-bg-surface); border-radius: 99px; overflow: hidden; }
.db-bar-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }
.db-kegiatan-nominal { font-size: 11px; color: var(--kd-text-muted); font-weight: 500; }

/* ── Belum Lunas Siswa ── */
.db-siswa-list { display: flex; flex-direction: column; max-height: 460px; overflow-y: auto; padding-right: 2px; }
.db-siswa-list::-webkit-scrollbar { width: 3px; }
.db-siswa-list::-webkit-scrollbar-thumb { background: var(--kd-border-strong); border-radius: 99px; }
.db-siswa-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--kd-border);
}
.db-siswa-row:last-child { border-bottom: none; }
.db-siswa-avatar {
    width: 32px; height: 32px;
    border-radius: 8px; border: 1.5px solid;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; flex-shrink: 0;
}
.db-siswa-name {
    flex: 1; font-size: 13px; font-weight: 600;
    color: var(--kd-text-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.db-belum-badge {
    font-size: 10px; font-weight: 700;
    background: var(--kd-warning-dim);
    color: var(--kd-warning);
    border: 1px solid var(--kd-warning);
    border-radius: 6px;
    padding: 2px 7px;
    letter-spacing: .02em;
    flex-shrink: 0;
}

/* ── Empty state ── */
.db-empty { text-align: center; padding: 32px 16px; color: var(--kd-text-muted); font-size: 13px; }
</style>
</x-app-layout>
