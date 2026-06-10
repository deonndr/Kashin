<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'KasDigital' }} — Kashin</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Overrides (Warm Dark Theme) -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
<div class="d-flex">

    <!-- SIDEBAR -->
    <aside class="kas-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand__icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="sidebar-brand__text">
                <div class="sidebar-brand__name">Kas<span>Digital</span></div>
                <div class="sidebar-brand__sub">Kelas XI RPL</div>
            </div>
        </div>
        
        <nav class="nav flex-column flex-grow-1">
            <span class="text-uppercase text-muted fw-bold small mb-2 px-2" style="font-size: 10px; letter-spacing: 0.8px;">Utama</span>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 fs-6"></i> Overview
            </a>
            <a href="{{ route('pembayaran-iuran.index') }}" class="nav-link {{ request()->routeIs('pembayaran-iuran.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2 fs-6"></i> Pembayaran
            </a>
            <a href="{{ route('kegiatan.index') }}" class="nav-link {{ request()->routeIs('kegiatan.*') || request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data fs-6"></i> Pengeluaran
            </a>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph fs-6"></i> Laporan
            </a>

            <span class="text-uppercase text-muted fw-bold small mt-3 mb-2 px-2" style="font-size: 10px; letter-spacing: 0.8px;">Data Master</span>
            <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="bi bi-people fs-6"></i> Siswa
            </a>
            <a href="{{ route('periode-iuran.index') }}" class="nav-link {{ request()->routeIs('periode-iuran.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3 fs-6"></i> Periode Iuran
            </a>
        </nav>

        <div class="pt-3 border-top border-secondary-subtle">
            <div class="text-muted small px-2">
                <i class="bi bi-shield-check me-2"></i>Kashin v1.0
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="kas-main flex-grow-1 d-flex flex-column">
        <!-- TOPBAR -->
        <header class="kas-topbar d-flex align-items-center justify-content-between px-4 sticky-top">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center p-0" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            style="width: 38px; height: 38px; font-weight: 700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-secondary-subtle mt-2">
                        <li class="dropdown-header">
                            <h6 class="text-light mb-0">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2" style="border: none; background: none; width: 100%;">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- PAGE BODY -->
        <main class="flex-grow-1">
            {{ $slot }}
        </main>
    </div>

</div>

<!-- Bootstrap 5.3 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}

// Global SweetAlert2 configuration for Warm Dark Mode
const kasSwal = Swal.mixin({
    background: '#1E1A15',
    color: '#F0EBE3',
    confirmButtonColor: '#7DA87B',
    cancelButtonColor: '#5C564E',
    customClass: {
        popup: 'border border-secondary-subtle rounded-3'
    }
});

// Intercept delete forms
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.classList.contains('delete-form')) {
            e.preventDefault();
            const form = e.target;
            const message = form.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
            
            kasSwal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // Session Notifications
    @if(session('success'))
        kasSwal.fire({
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            icon: 'success',
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        kasSwal.fire({
            title: 'Gagal!',
            text: {!! json_encode(session('error')) !!},
            icon: 'error',
            confirmButtonText: 'Tutup'
        });
    @endif
});
</script>
</body>
</html>
