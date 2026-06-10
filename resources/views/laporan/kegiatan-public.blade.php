<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transparansi Kegiatan — Kashin</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Overrides (Warm Dark Theme) -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="student-layout-body">

<div class="container py-3" style="max-width: 720px;">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-display text-white mb-0">Transparansi Kegiatan</h2>
            <p class="font-caption mb-0">Rincian penggunaan kas kelas secara transparan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.siswa') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    @forelse($kegiatans as $i => $k)
        @php
            $pct = $k['estimasi'] > 0 ? min(100, round($k['realisasi'] / $k['estimasi'] * 100)) : 0;
            if ($pct <= 33) {
                $c = 'danger';
            } elseif ($pct <= 66) {
                $c = 'warning';
            } else {
                $c = 'success';
            }
        @endphp
        <div class="card kas-card mb-3">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <h5 class="font-heading text-white mb-0">{{ $k['nama'] }}</h5>
                <span class="kas-badge kas-badge-{{ $c }} py-1 px-2" style="font-size: 11px !important;">{{ $pct }}% terpakai</span>
            </div>
            <div class="d-flex gap-4 mb-3">
                <span class="font-caption">
                    <i class="bi bi-calculator me-1"></i> Estimasi: <strong class="text-light">Rp {{ number_format($k['estimasi'], 0, ',', '.') }}</strong>
                </span>
                <span class="text-danger font-caption">
                    <i class="bi bi-arrow-down-circle me-1"></i> Terpakai: <strong>Rp {{ number_format($k['realisasi'], 0, ',', '.') }}</strong>
                </span>
            </div>
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar bg-{{ $c }}" role="progressbar" style="width: {{ $pct }}%"></div>
            </div>

            @foreach($k['pengeluaran'] as $p)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary-subtle">
                    <div>
                        <span class="text-secondary font-body">{{ $p->nama_pengeluaran }}</span>
                        @if($p->tanggal_keluar)
                            <div class="font-caption" style="font-size: 11px;">
                                <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($p->tanggal_keluar)->translatedFormat('d M Y') }}
                            </div>
                        @endif
                    </div>
                    <span class="text-danger fw-bold font-body">-Rp {{ number_format($p->nominal_keluar, 0, ',', '.') }}</span>
                </div>
            @endforeach

            @if(count($k['pengeluaran']) === 0)
                <p class="font-caption text-center mt-2 mb-0">Belum ada rincian pengeluaran</p>
            @endif
        </div>
    @empty
        <div class="card kas-card text-center text-muted">
            <i class="bi bi-clipboard-x fs-1 mb-3 d-block"></i>
            <div class="font-body fw-semibold">Belum ada data kegiatan</div>
        </div>
    @endforelse
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
