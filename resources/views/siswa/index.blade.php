<x-app-layout>
    <x-slot name="title">Data Siswa</x-slot>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white py-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Data Siswa</h2>
            <p class="text-muted small mb-0" id="siswaCount">Total {{ $siswas->count() }} siswa terdaftar</p>
        </div>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-person-plus"></i> Tambah Siswa
        </a>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="ks-search-wrap">
        <i class="bi bi-search ks-search-icon"></i>
        <input type="text" id="siswaSearch" class="ks-search-input" placeholder="Cari nama siswa atau NISN...">
        <span class="ks-search-count" id="searchCount"></span>
    </div>

    {{-- ── Grid ── --}}
    <div class="ks-grid" id="siswaGrid">
        @forelse($siswas as $siswa)
            @php
                $kata = explode(' ', $siswa->nama_siswa);
                $inisial = strtoupper(substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : ''));
                $palette = ['#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#8b5cf6','#10b981','#f97316'];
                $color   = $palette[$siswa->id % count($palette)];
            @endphp
            <div class="ks-card"
                 data-nama="{{ strtolower($siswa->nama_siswa) }}"
                 data-nisn="{{ $siswa->nisn }}">
                {{-- Avatar --}}
                <div class="ks-avatar" style="background:{{ $color }}18; color:{{ $color }}; border-color:{{ $color }}35;">
                    {{ $inisial }}
                </div>
                {{-- Info --}}
                <div class="ks-info">
                    <div class="ks-name">{{ $siswa->nama_siswa }}</div>
                    <div class="ks-meta">
                        <code class="ks-nisn">{{ $siswa->nisn }}</code>
                        <span class="ks-kelas-badge" style="background:{{ $color }}18; color:{{ $color }}; border-color:{{ $color }}30;">
                            {{ $siswa->kelas }}
                        </span>
                    </div>
                </div>
                {{-- Actions --}}
                <div class="ks-actions">
                    <a href="{{ route('siswa.edit', $siswa) }}"
                       class="ks-btn-icon" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="{{ route('siswa.destroy', $siswa) }}"
                          class="delete-form" data-message="Hapus siswa {{ $siswa->nama_siswa }}? Semua data pembayarannya ikut terhapus.">
                        @csrf @method('DELETE')
                        <button type="submit" class="ks-btn-icon ks-btn-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="ks-empty">
                <i class="bi bi-people fs-1 mb-2 d-block"></i>
                <div class="fw-semibold">Belum ada siswa</div>
                <small>Klik "Tambah Siswa" untuk memulai pendaftaran.</small>
            </div>
        @endforelse
    </div>

    {{-- ── No result message ── --}}
    <div class="ks-no-result d-none" id="noResult">
        <i class="bi bi-search fs-2 mb-2 d-block"></i>
        Tidak ada siswa yang cocok
    </div>

<style>
/* ── Search ── */
.ks-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.ks-search-icon {
    position: absolute;
    left: 16px;
    color: var(--kd-text-muted);
    font-size: 14px;
    pointer-events: none;
}
.ks-search-input {
    width: 100%;
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 12px;
    color: var(--kd-text-primary);
    padding: 12px 16px 12px 42px;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.ks-search-input:focus {
    border-color: var(--kd-accent);
    box-shadow: 0 0 0 3px #6282ed1a;
}
.ks-search-input::placeholder { color: var(--kd-text-muted); }
.ks-search-count {
    position: absolute;
    right: 16px;
    font-size: 12px;
    color: var(--kd-text-muted);
    font-weight: 600;
    pointer-events: none;
}

/* ── Grid ── */
.ks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 12px;
}

/* ── Card ── */
.ks-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 14px;
    transition: border-color .18s, background .18s, transform .12s, box-shadow .18s;
    position: relative;
}
.ks-card:hover {
    border-color: var(--kd-border-strong);
    background: var(--kd-bg-elevated);
    transform: translateY(-2px);
    box-shadow: 0 4px 20px #00000030;
}
.ks-card:hover .ks-actions { opacity: 1; }

/* ── Avatar ── */
.ks-avatar {
    width: 44px; height: 44px;
    border-radius: 12px;
    border: 1.5px solid;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800;
    flex-shrink: 0;
}

/* ── Info ── */
.ks-info { min-width: 0; flex: 1; }
.ks-name {
    font-size: 14px; font-weight: 700;
    color: var(--kd-text-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 4px;
}
.ks-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.ks-nisn {
    font-size: 11px;
    background: var(--kd-bg-elevated);
    color: var(--kd-text-muted);
    border: 1px solid var(--kd-border);
    border-radius: 5px;
    padding: 1px 6px;
    font-family: 'Courier New', monospace;
}
.ks-kelas-badge {
    font-size: 11px; font-weight: 700;
    border: 1px solid;
    border-radius: 6px;
    padding: 1px 8px;
    letter-spacing: .02em;
}

/* ── Actions ── */
.ks-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity .18s;
}
@media (hover: none) { .ks-actions { opacity: 1; } } /* always show on touch */
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
.ks-btn-icon:hover { border-color: var(--kd-accent); color: var(--kd-accent); background: var(--kd-accent-dim); }
.ks-btn-danger:hover { border-color: var(--kd-danger) !important; color: var(--kd-danger) !important; background: var(--kd-danger-dim) !important; }

/* ── Empty / No Result ── */
.ks-empty { grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--kd-text-muted); }
.ks-no-result { text-align: center; padding: 60px 20px; color: var(--kd-text-muted); }
</style>

<script>
const searchInput  = document.getElementById('siswaSearch');
const cards        = document.querySelectorAll('.ks-card');
const noResult     = document.getElementById('noResult');
const searchCount  = document.getElementById('searchCount');
const siswaCount   = document.getElementById('siswaCount');
const total        = cards.length;

searchInput.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    let visible = 0;

    cards.forEach(card => {
        const match = !q || card.dataset.nama.includes(q) || card.dataset.nisn.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    noResult.classList.toggle('d-none', visible > 0);
    searchCount.textContent = q ? `${visible} dari ${total}` : '';
    siswaCount.textContent  = q
        ? `Menampilkan ${visible} dari ${total} siswa`
        : `Total ${total} siswa terdaftar`;
});
</script>
</x-app-layout>
