<x-app-layout>
    <x-slot name="title">Catat Pembayaran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Catat Pembayaran</h2>
            <p class="text-muted small mb-0">Rekam transaksi iuran masuk</p>
        </div>
        <a href="{{ route('pembayaran-iuran.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ═══ WIZARD CONTAINER ═══ --}}
    <div class="kw-wizard-wrap">

        {{-- ── STEP INDICATOR ── --}}
        <div class="kw-steps">
            <div class="kw-step active" id="ind-1">
                <div class="kw-step__circle">1</div>
                <span class="kw-step__label">Pilih Siswa</span>
            </div>
            <div class="kw-step__line"></div>
            <div class="kw-step" id="ind-2">
                <div class="kw-step__circle">2</div>
                <span class="kw-step__label">Detail Bayar</span>
            </div>
            <div class="kw-step__line"></div>
            <div class="kw-step" id="ind-3">
                <div class="kw-step__circle">3</div>
                <span class="kw-step__label">Konfirmasi</span>
            </div>
        </div>

        {{-- ── FORM (wraps all 3 panels) ── --}}
        <form method="POST" action="{{ route('pembayaran-iuran.store') }}" id="kwForm">
            @csrf
            {{-- hidden inputs yang akan diisi JS --}}
            <input type="hidden" name="siswa_id"         id="h_siswa_id">
            <input type="hidden" name="periode_iuran_id" id="h_periode_id">
            <input type="hidden" name="jumlah_bayar"     id="h_jumlah">
            <input type="hidden" name="tanggal_bayar"    id="h_tanggal">
            <input type="hidden" name="status_bayar"     id="h_status">

            {{-- ═══ STEP 1: Pilih Siswa ═══ --}}
            <div class="kw-panel active" id="panel-1">
                <div class="kw-panel__head">
                    <h5 class="kw-panel__title">Siapa yang membayar?</h5>
                    <p class="kw-panel__sub">Siswa belum bayar bulan ini ditampilkan paling atas</p>
                </div>

                {{-- Search --}}
                <div class="kw-search-wrap">
                    <i class="bi bi-search kw-search-icon"></i>
                    <input type="text" id="siswaSearch" class="kw-search-input" placeholder="Cari nama siswa...">
                </div>

                {{-- Belum Bayar --}}
                @if($belumBayar->isNotEmpty())
                <div class="kw-group-label">
                    <span class="kw-dot kw-dot--warn"></span>
                    Belum Bayar Bulan Ini · {{ $belumBayar->count() }} siswa
                </div>
                <div class="kw-student-grid" id="studentGrid">
                    @foreach($belumBayar as $s)
                        @php
                            $kata = explode(' ', $s->nama_siswa);
                            $inisial = strtoupper(substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : ''));
                            $colors = ['#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#8b5cf6'];
                            $color  = $colors[$s->id % count($colors)];
                        @endphp
                        <div class="kw-student-card unpaid"
                             data-id="{{ $s->id }}"
                             data-nama="{{ $s->nama_siswa }}"
                             data-kelas="{{ $s->kelas }}"
                             data-color="{{ $color }}"
                             data-inisial="{{ $inisial }}"
                             onclick="selectSiswa(this)">
                            <div class="kw-avatar" style="background:{{ $color }}1A; color:{{ $color }}; border-color:{{ $color }}40;">
                                {{ $inisial }}
                            </div>
                            <div class="kw-student-info">
                                <div class="kw-student-name">{{ $s->nama_siswa }}</div>
                                <div class="kw-student-kelas">{{ $s->kelas }}</div>
                            </div>
                            <span class="kw-status-dot kw-dot--warn"></span>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- Sudah Bayar --}}
                @if($sudahBayar->isNotEmpty())
                <div class="kw-group-label mt-4">
                    <span class="kw-dot kw-dot--ok"></span>
                    Sudah Bayar · {{ $sudahBayar->count() }} siswa
                </div>
                <div class="kw-student-grid" id="studentGrid2">
                    @foreach($sudahBayar as $s)
                        @php
                            $kata = explode(' ', $s->nama_siswa);
                            $inisial = strtoupper(substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : ''));
                            $colors = ['#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444','#06b6d4','#ec4899','#8b5cf6'];
                            $color  = $colors[$s->id % count($colors)];
                        @endphp
                        <div class="kw-student-card paid"
                             data-id="{{ $s->id }}"
                             data-nama="{{ $s->nama_siswa }}"
                             data-kelas="{{ $s->kelas }}"
                             data-color="{{ $color }}"
                             data-inisial="{{ $inisial }}"
                             onclick="selectSiswa(this)">
                            <div class="kw-avatar" style="background:{{ $color }}1A; color:{{ $color }}; border-color:{{ $color }}40; opacity:.6;">
                                {{ $inisial }}
                            </div>
                            <div class="kw-student-info">
                                <div class="kw-student-name" style="opacity:.65;">{{ $s->nama_siswa }}</div>
                                <div class="kw-student-kelas">{{ $s->kelas }}</div>
                            </div>
                            <span class="kw-status-dot kw-dot--ok"></span>
                        </div>
                    @endforeach
                </div>
                @endif

                @error('siswa_id')
                    <div class="kw-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- ═══ STEP 2: Detail Bayar ═══ --}}
            <div class="kw-panel" id="panel-2">
                <div class="kw-panel__head">
                    {{-- Selected student recap --}}
                    <div class="kw-selected-recap" id="recapSiswa"></div>
                    <p class="kw-panel__sub mt-2">Lengkapi detail pembayaran</p>
                </div>

                {{-- Periode sebagai pill cards --}}
                <div class="kw-field-label">Periode Iuran</div>
                <div class="kw-periode-grid">
                    @foreach($periodes as $p)
                        <label class="kw-periode-pill">
                            <input type="radio" name="_periode_pick" value="{{ $p->id }}"
                                   data-nominal="{{ $p->nominal_tagihan }}"
                                   data-nama="{{ $p->nama_periode }}"
                                   onchange="onPeriodePick(this)">
                            <div class="kw-periode-pill__inner">
                                <div class="kw-periode-pill__name">{{ $p->nama_periode }}</div>
                                <div class="kw-periode-pill__nominal">Rp {{ number_format($p->nominal_tagihan, 0, ',', '.') }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('periode_iuran_id')
                    <div class="kw-error-msg">{{ $message }}</div>
                @enderror

                {{-- Jumlah & Tanggal --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="kw-field-label">Jumlah Bayar (Rp)</div>
                        <div class="kw-input-wrap">
                            <span class="kw-input-prefix">Rp</span>
                            <input type="number" id="jumlahInput" class="kw-input" placeholder="50000" min="1000"
                                   oninput="syncFields()">
                        </div>
                        @error('jumlah_bayar')
                            <div class="kw-error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="kw-field-label">Tanggal Bayar</div>
                        <input type="date" id="tanggalInput" class="kw-input" value="{{ date('Y-m-d') }}"
                               oninput="syncFields()">
                        @error('tanggal_bayar')
                            <div class="kw-error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Status toggle --}}
                <div class="kw-field-label mt-3">Status</div>
                <div class="kw-toggle-group">
                    <label class="kw-toggle-btn active" id="tgl-lunas">
                        <input type="radio" name="_status_pick" value="Lunas" checked onchange="onStatusPick('Lunas')">
                        <i class="bi bi-check-circle-fill me-1"></i> Lunas
                    </label>
                    <label class="kw-toggle-btn" id="tgl-belum">
                        <input type="radio" name="_status_pick" value="Belum Lunas" onchange="onStatusPick('Belum Lunas')">
                        <i class="bi bi-clock-fill me-1"></i> Belum Lunas
                    </label>
                </div>
                @error('status_bayar')
                    <div class="kw-error-msg">{{ $message }}</div>
                @enderror

                <div class="kw-nav mt-4">
                    <button type="button" class="kw-btn-ghost" onclick="goStep(1)">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </button>
                    <button type="button" class="kw-btn-primary" onclick="goStep(3)">
                        Lanjut Review <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- ═══ STEP 3: Konfirmasi ═══ --}}
            <div class="kw-panel" id="panel-3">
                <div class="kw-panel__head">
                    <h5 class="kw-panel__title">Konfirmasi Pembayaran</h5>
                    <p class="kw-panel__sub">Cek kembali sebelum menyimpan</p>
                </div>

                <div class="kw-review-card">
                    <div class="kw-review-row">
                        <span class="kw-review-label">Siswa</span>
                        <span class="kw-review-val" id="rv-siswa">—</span>
                    </div>
                    <div class="kw-review-row">
                        <span class="kw-review-label">Kelas</span>
                        <span class="kw-review-val" id="rv-kelas">—</span>
                    </div>
                    <div class="kw-review-row">
                        <span class="kw-review-label">Periode</span>
                        <span class="kw-review-val" id="rv-periode">—</span>
                    </div>
                    <div class="kw-review-row">
                        <span class="kw-review-label">Jumlah Bayar</span>
                        <span class="kw-review-val kw-review-amount" id="rv-jumlah">—</span>
                    </div>
                    <div class="kw-review-row">
                        <span class="kw-review-label">Tanggal</span>
                        <span class="kw-review-val" id="rv-tanggal">—</span>
                    </div>
                    <div class="kw-review-row">
                        <span class="kw-review-label">Status</span>
                        <span class="kw-review-val" id="rv-status">—</span>
                    </div>
                </div>

                <div class="kw-nav mt-4">
                    <button type="button" class="kw-btn-ghost" onclick="goStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Ubah
                    </button>
                    <button type="submit" class="kw-btn-save">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pembayaran
                    </button>
                </div>
            </div>

        </form>
    </div>

{{-- ═══ STYLES ═══ --}}
<style>
/* ── Wizard Wrap ── */
.kw-wizard-wrap {
    background: var(--kd-bg-surface);
    border: 1px solid var(--kd-border);
    border-radius: 16px;
    padding: 32px 40px;
}

/* ── Step Indicator ── */
.kw-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 36px;
}
.kw-step {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.kw-step__circle {
    width: 32px; height: 32px;
    border-radius: 50%;
    border: 2px solid var(--kd-border-strong);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    color: var(--kd-text-muted);
    background: var(--kd-bg-elevated);
    transition: all .25s ease;
}
.kw-step__label {
    font-size: 12px;
    font-weight: 600;
    color: var(--kd-text-muted);
    letter-spacing: .03em;
    text-transform: uppercase;
    transition: color .25s;
}
.kw-step.active .kw-step__circle {
    background: var(--kd-accent);
    border-color: var(--kd-accent);
    color: #fff;
    box-shadow: 0 0 0 4px #6282ed28;
}
.kw-step.active .kw-step__label { color: var(--kd-accent); }
.kw-step.done .kw-step__circle {
    background: #22c55e20;
    border-color: #22c55e;
    color: #22c55e;
}
.kw-step.done .kw-step__label { color: #22c55e; }
.kw-step__line {
    flex: 1;
    height: 2px;
    background: var(--kd-border);
    margin: 0 12px;
    border-radius: 2px;
    transition: background .3s;
}
.kw-step__line.done { background: #22c55e; }

/* ── Panels ── */
.kw-panel { display: none; animation: kwFadeIn .22s ease; }
.kw-panel.active { display: block; }
@keyframes kwFadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }

.kw-panel__head { margin-bottom: 24px; }
.kw-panel__title { font-size: 1.1rem; font-weight: 700; color: var(--kd-text-primary); margin-bottom: 4px; }
.kw-panel__sub { font-size: 12px; color: var(--kd-text-muted); margin: 0; }

/* ── Search ── */
.kw-search-wrap {
    position: relative;
    margin-bottom: 20px;
}
.kw-search-icon {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    color: var(--kd-text-muted);
    font-size: 14px;
}
.kw-search-input {
    width: 100%;
    background: var(--kd-bg-elevated);
    border: 1px solid var(--kd-border);
    border-radius: 10px;
    color: var(--kd-text-primary);
    padding: 10px 14px 10px 38px;
    font-size: 13px;
    outline: none;
    transition: border-color .2s;
}
.kw-search-input:focus { border-color: var(--kd-accent); }
.kw-search-input::placeholder { color: var(--kd-text-muted); }

/* ── Group label ── */
.kw-group-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--kd-text-muted);
    margin-bottom: 12px;
}
.kw-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
.kw-dot--warn { background: #C49A3C; }
.kw-dot--ok   { background: #22c55e; }

/* ── Student Grid ── */
.kw-student-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 10px;
    max-height: 420px;
    overflow-y: auto;
    padding-right: 4px;
}
.kw-student-grid::-webkit-scrollbar { width: 4px; }
.kw-student-grid::-webkit-scrollbar-track { background: transparent; }
.kw-student-grid::-webkit-scrollbar-thumb { background: var(--kd-border-strong); border-radius: 4px; }

.kw-student-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 12px;
    cursor: pointer;
    transition: border-color .18s, background .18s, transform .12s;
    position: relative;
}
.kw-student-card:hover {
    border-color: var(--kd-border-strong);
    background: #ffffff08;
    transform: translateY(-1px);
}
.kw-student-card.selected {
    border-color: var(--kd-accent) !important;
    background: var(--kd-accent-dim) !important;
    box-shadow: 0 0 0 3px #6282ed20;
}

.kw-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    border: 1.5px solid;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800;
    flex-shrink: 0;
    transition: opacity .2s;
}
.kw-student-info { min-width: 0; flex: 1; }
.kw-student-name {
    font-size: 13px; font-weight: 600;
    color: var(--kd-text-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.kw-student-kelas { font-size: 11px; color: var(--kd-text-muted); }

.kw-status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-left: auto;
}

/* ── Selected Recap (Step 2 header) ── */
.kw-selected-recap {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--kd-bg-elevated);
    border: 1px solid var(--kd-border);
    border-radius: 12px;
    padding: 12px 16px;
}
.kw-recap-avatar {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800;
    border: 1.5px solid;
}
.kw-recap-name { font-size: 15px; font-weight: 700; color: var(--kd-text-primary); }
.kw-recap-kelas { font-size: 12px; color: var(--kd-text-muted); }

/* ── Periode pills ── */
.kw-periode-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
    margin-top: 10px;
}
.kw-periode-pill input { display: none; }
.kw-periode-pill__inner {
    padding: 10px 18px;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    cursor: pointer;
    transition: border-color .18s, background .18s;
    text-align: center;
    min-width: 120px;
}
.kw-periode-pill:has(input:checked) .kw-periode-pill__inner {
    border-color: var(--kd-accent);
    background: var(--kd-accent-dim);
}
.kw-periode-pill__name { font-size: 13px; font-weight: 600; color: var(--kd-text-primary); }
.kw-periode-pill__nominal { font-size: 11px; color: var(--kd-text-muted); margin-top: 2px; }

/* ── Inputs ── */
.kw-field-label {
    font-size: 11px; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--kd-text-muted);
    margin-bottom: 8px;
}
.kw-input-wrap { position: relative; }
.kw-input-prefix {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: 13px; color: var(--kd-text-muted); font-weight: 600;
}
.kw-input {
    width: 100%;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    color: var(--kd-text-primary);
    padding: 10px 14px;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
}
.kw-input-wrap .kw-input { padding-left: 36px; }
.kw-input:focus { border-color: var(--kd-accent); }
input[type="date"].kw-input::-webkit-calendar-picker-indicator { filter: invert(.5); }

/* ── Status Toggle ── */
.kw-toggle-group {
    display: flex;
    gap: 10px;
}
.kw-toggle-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    background: var(--kd-bg-elevated);
    color: var(--kd-text-muted);
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    transition: all .18s;
}
.kw-toggle-btn input { display: none; }
.kw-toggle-btn.active {
    border-color: #22c55e;
    background: #22c55e18;
    color: #22c55e;
}
.kw-toggle-btn.warn {
    border-color: var(--kd-warning);
    background: var(--kd-warning-dim);
    color: var(--kd-warning);
}

/* ── Review Card ── */
.kw-review-card {
    background: var(--kd-bg-elevated);
    border: 1px solid var(--kd-border);
    border-radius: 14px;
    overflow: hidden;
}
.kw-review-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 20px;
    border-bottom: 1px solid var(--kd-border);
}
.kw-review-row:last-child { border-bottom: none; }
.kw-review-label { font-size: 12px; color: var(--kd-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
.kw-review-val { font-size: 14px; font-weight: 600; color: var(--kd-text-primary); }
.kw-review-amount { font-size: 18px; color: #22c55e; font-weight: 800; }

/* ── Navigation ── */
.kw-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.kw-btn-ghost {
    background: none;
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    color: var(--kd-text-muted);
    padding: 9px 20px;
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: border-color .18s, color .18s;
}
.kw-btn-ghost:hover { border-color: var(--kd-border-strong); color: var(--kd-text-primary); }
.kw-btn-primary {
    background: var(--kd-accent);
    border: none;
    border-radius: 10px;
    color: #fff;
    padding: 10px 24px;
    font-size: 13px; font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: opacity .18s, transform .12s;
}
.kw-btn-primary:hover { opacity: .88; transform: translateY(-1px); }
.kw-btn-save {
    background: #22c55e;
    border: none;
    border-radius: 10px;
    color: #fff;
    padding: 10px 28px;
    font-size: 14px; font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: opacity .18s, transform .12s;
    box-shadow: 0 4px 20px #22c55e30;
}
.kw-btn-save:hover { opacity: .88; transform: translateY(-1px); }

/* ── Error ── */
.kw-error-msg { font-size: 12px; color: var(--kd-danger); margin-top: 6px; }

/* ── Hidden util ── */
.kw-hidden { display: none !important; }
</style>

{{-- ═══ SCRIPT ═══ --}}
<script>
/* ─── State ─── */
let state = {
    step: 1,
    siswaId: null, siswaName: '', siswaKelas: '', siswaColor: '', siswaInisial: '',
    periodeId: null, periodeNama: '', nominal: 0,
    jumlah: 0, tanggal: '{{ date("Y-m-d") }}', status: 'Lunas'
};

/* ─── Step navigation ─── */
function goStep(n) {
    if (n === 2 && !state.siswaId) {
        alert('Pilih siswa terlebih dahulu!'); return;
    }
    if (n === 3) {
        if (!state.periodeId) { alert('Pilih periode iuran!'); return; }
        if (!state.jumlah || state.jumlah < 1000) { alert('Isi jumlah bayar (min Rp 1.000)!'); return; }
        if (!state.tanggal) { alert('Isi tanggal bayar!'); return; }
        buildReview();
        syncHiddens();
    }
    // hide all panels
    document.querySelectorAll('.kw-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + n).classList.add('active');

    // update step indicators
    document.querySelectorAll('.kw-step').forEach((el, i) => {
        el.classList.remove('active', 'done');
        const idx = i / 2 + 1; // steps are at index 0, 2, 4
    });
    const steps = document.querySelectorAll('.kw-step');
    const lines = document.querySelectorAll('.kw-step__line');
    steps.forEach((s, i) => {
        const stepNum = i + 1;
        s.classList.remove('active','done');
        if (stepNum < n) { s.classList.add('done'); }
        else if (stepNum === n) { s.classList.add('active'); }
    });
    lines.forEach((l, i) => {
        l.classList.toggle('done', i + 1 < n);
    });
    state.step = n;
}

/* ─── Step 1: Select student ─── */
function selectSiswa(el) {
    document.querySelectorAll('.kw-student-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    state.siswaId     = el.dataset.id;
    state.siswaName   = el.dataset.nama;
    state.siswaKelas  = el.dataset.kelas;
    state.siswaColor  = el.dataset.color;
    state.siswaInisial= el.dataset.inisial;

    // Build recap for step 2
    document.getElementById('recapSiswa').innerHTML = `
        <div class="kw-recap-avatar" style="background:${state.siswaColor}1A;color:${state.siswaColor};border-color:${state.siswaColor}50;">
            ${state.siswaInisial}
        </div>
        <div>
            <div class="kw-recap-name">${state.siswaName}</div>
            <div class="kw-recap-kelas">${state.siswaKelas}</div>
        </div>`;

    // Auto advance after small delay for feel
    setTimeout(() => goStep(2), 180);
}

/* ─── Step 2: Periode pick ─── */
function onPeriodePick(el) {
    state.periodeId   = el.value;
    state.periodeNama = el.dataset.nama;
    state.nominal     = parseInt(el.dataset.nominal);
    // Auto-fill jumlah with nominal
    document.getElementById('jumlahInput').value = state.nominal;
    state.jumlah = state.nominal;
    syncFields();
}

function onStatusPick(val) {
    state.status = val;
    document.getElementById('tgl-lunas').classList.toggle('active', val === 'Lunas');
    document.getElementById('tgl-belum').classList.remove('active','warn');
    if (val === 'Belum Lunas') document.getElementById('tgl-belum').classList.add('warn');
}

function syncFields() {
    state.jumlah  = parseInt(document.getElementById('jumlahInput').value) || 0;
    state.tanggal = document.getElementById('tanggalInput').value;
}

/* ─── Step 3: Review ─── */
function buildReview() {
    syncFields();
    document.getElementById('rv-siswa').textContent  = state.siswaName;
    document.getElementById('rv-kelas').textContent  = state.siswaKelas;
    document.getElementById('rv-periode').textContent = state.periodeNama || '—';
    document.getElementById('rv-jumlah').textContent  = 'Rp ' + state.jumlah.toLocaleString('id-ID');
    document.getElementById('rv-tanggal').textContent = state.tanggal
        ? new Date(state.tanggal).toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'})
        : '—';
    const statusEl = document.getElementById('rv-status');
    statusEl.textContent = state.status;
    statusEl.style.color = state.status === 'Lunas' ? '#22c55e' : 'var(--kd-warning)';
}

function syncHiddens() {
    document.getElementById('h_siswa_id').value    = state.siswaId;
    document.getElementById('h_periode_id').value  = state.periodeId;
    document.getElementById('h_jumlah').value      = state.jumlah;
    document.getElementById('h_tanggal').value     = state.tanggal;
    document.getElementById('h_status').value      = state.status;
}

/* ─── Search filter ─── */
document.getElementById('siswaSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.kw-student-card').forEach(card => {
        const nama = card.dataset.nama.toLowerCase();
        const kelas = card.dataset.kelas.toLowerCase();
        card.style.display = (!q || nama.includes(q) || kelas.includes(q)) ? '' : 'none';
    });
});

/* ─── Re-init on page load (handle old() validation errors) ─── */
@if(old('siswa_id'))
    document.addEventListener('DOMContentLoaded', () => {
        const card = document.querySelector('.kw-student-card[data-id="{{ old('siswa_id') }}"]');
        if (card) selectSiswa(card);
    });
@endif
</script>
</x-app-layout>
