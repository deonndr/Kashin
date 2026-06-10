<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Siswa — Kashin</title>
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

<div class="container py-3" style="max-width: 660px;">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center fw-bold text-white"
                 style="width: 44px; height: 44px; font-size: 15px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div class="fw-bold text-white">{{ $siswa->nama_siswa ?? auth()->user()->name }}</div>
                <div class="text-muted small">{{ $siswa->kelas ?? '' }} · Siswa</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.kegiatan') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-file-bar-graph"></i> Lap. Kegiatan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    @if(!$siswa)
        <div class="card kas-card text-center">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-1 mb-3 d-block"></i>
            <h5 class="font-heading text-light mb-2">Akun Belum Terhubung</h5>
            <p class="font-caption mb-0">Hubungi bendahara (<strong>{{ $bendaharaEmail }}</strong>) untuk menghubungkan akunmu ke data siswa.</p>
        </div>
    @else

        {{-- Status Bulan Ini --}}
        <div class="card kas-card text-center mb-4 py-4">
            @if($statusBulanIni && $statusBulanIni->status_bayar === 'Lunas')
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-check-circle-fill" style="font-size: 28px; line-height: 1;"></i>
                </div>
                <h4 class="font-heading text-success mb-2 fw-bold">Sudah Lunas!</h4>
                <p class="font-caption mb-0">
                    Iuran bulan {{ now()->translatedFormat('F Y') }} telah dibayar sebesar
                    <strong class="text-light">Rp {{ number_format($statusBulanIni->jumlah_bayar, 0, ',', '.') }}</strong>
                </p>
            @elseif($statusBulanIni && $statusBulanIni->status_bayar === 'Belum Lunas')
                <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-clock-fill" style="font-size: 28px; line-height: 1;"></i>
                </div>
                <h4 class="font-heading text-warning mb-2 fw-bold">Belum Lunas</h4>
                <p class="font-caption mb-0">
                    Iuran bulan {{ now()->translatedFormat('F Y') }} belum dilunasi sepenuhnya
                </p>
            @else
                <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-x-circle-fill" style="font-size: 28px; line-height: 1;"></i>
                </div>
                <h4 class="font-heading text-danger mb-2 fw-bold">Belum Bayar</h4>
                <p class="font-caption mb-0 text-secondary">
                    Tidak ada catatan pembayaran untuk bulan {{ now()->translatedFormat('F Y') }}
                </p>
            @endif
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="card kas-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading text-white mb-0">Riwayat Iuranku</h5>
                <span class="kas-badge kas-badge-secondary py-1 px-2">
                    {{ $riwayatBayar->count() }} transaksi
                </span>
            </div>

            <div class="list-group list-group-flush">
                @forelse($riwayatBayar as $r)
                    <div class="list-group-item bg-transparent border-secondary-subtle px-0 py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center {{ $r->status_bayar === 'Lunas' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}"
                                 style="width: 40px; height: 40px;">
                                <i class="bi {{ $r->status_bayar === 'Lunas' ? 'bi-check2' : 'bi-hourglass-split' }}" style="font-size: 18px;"></i>
                            </div>
                            <div>
                                <div class="font-body text-light fw-semibold">{{ $r->periodeIuran->nama_periode ?? 'Periode' }}</div>
                                <div class="font-caption">{{ \Carbon\Carbon::parse($r->tanggal_bayar)->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="font-body fw-bold {{ $r->status_bayar === 'Lunas' ? 'text-success' : 'text-warning' }}">
                                Rp {{ number_format($r->jumlah_bayar, 0, ',', '.') }}
                            </div>
                            <span class="kas-badge {{ $r->status_bayar === 'Lunas' ? 'kas-badge-success' : 'kas-badge-warning' }} mt-1 py-1 px-2" style="font-size: 11px !important;">
                                {{ $r->status_bayar }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 font-caption">
                        <i class="bi bi-receipt fs-1 mb-2 d-block"></i>
                        <div class="font-body fw-semibold">Belum ada riwayat pembayaran</div>
                    </div>
                @endforelse
            </div>
        </div>

    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
