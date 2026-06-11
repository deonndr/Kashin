<x-app-layout>
    <x-slot name="title">Tambah Pengeluaran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Tambah Pengeluaran</h2>
            <p class="text-muted small mb-0">Catat rincian biaya kegiatan</p>
        </div>
        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ── Kegiatan Context Banner ── --}}
    <div class="kpe-context-banner">
        <div class="kpe-context-icon">
            <i class="bi bi-calendar-event-fill"></i>
        </div>
        <div>
            <div class="kpe-context-label">Kegiatan</div>
            <div class="kpe-context-name">{{ $kegiatan->nama_kegiatan }}</div>
        </div>
        <div class="kpe-context-budget ms-auto text-end">
            <div class="kpe-context-label">Estimasi Anggaran</div>
            <div class="kpe-context-nominal">Rp {{ number_format($kegiatan->estimasi_biaya, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- ── Form ── --}}
    <div class="kpe-form-wrap">
        <form method="POST" action="{{ route('pengeluaran.store', $kegiatan) }}" id="kpeForm">
            @csrf

            {{-- Nama Item --}}
            <div class="kpe-field">
                <label class="kpe-label" for="nama_pengeluaran">
                    <i class="bi bi-tag-fill me-1"></i> Nama Item Pengeluaran
                </label>
                <input type="text" id="nama_pengeluaran" name="nama_pengeluaran"
                       class="kpe-input @error('nama_pengeluaran') kpe-input--error @enderror"
                       value="{{ old('nama_pengeluaran') }}"
                       placeholder="Contoh: Sewa Bus, Pembelian ATK, Konsumsi..."
                       autofocus>
                @error('nama_pengeluaran')
                    <div class="kpe-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nominal & Tanggal side by side --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="kpe-field">
                        <label class="kpe-label" for="nominal_keluar">
                            <i class="bi bi-currency-dollar me-1"></i> Nominal (Rp)
                        </label>
                        <div class="kpe-input-wrap">
                            <span class="kpe-prefix">Rp</span>
                            <input type="number" id="nominal_keluar" name="nominal_keluar"
                                   class="kpe-input kpe-input--prefix @error('nominal_keluar') kpe-input--error @enderror"
                                   value="{{ old('nominal_keluar') }}"
                                   placeholder="120000" min="1"
                                   oninput="updatePreview()">
                        </div>
                        @error('nominal_keluar')
                            <div class="kpe-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="kpe-field">
                        <label class="kpe-label" for="tanggal_keluar">
                            <i class="bi bi-calendar3 me-1"></i> Tanggal
                            <span class="kpe-optional">opsional</span>
                        </label>
                        <input type="date" id="tanggal_keluar" name="tanggal_keluar"
                               class="kpe-input @error('tanggal_keluar') kpe-input--error @enderror"
                               value="{{ old('tanggal_keluar', date('Y-m-d')) }}">
                        @error('tanggal_keluar')
                            <div class="kpe-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="kpe-preview" id="kpePreview" style="display:none;">
                <div class="kpe-preview__label"><i class="bi bi-receipt me-1"></i> Preview</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="kpe-preview__name" id="prevNama">—</span>
                    <span class="kpe-preview__amount" id="prevNominal">—</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="kpe-actions">
                <a href="{{ route('kegiatan.show', $kegiatan) }}" class="kpe-btn-ghost">Batal</a>
                <button type="submit" class="kpe-btn-primary">
                    <i class="bi bi-plus-circle-fill me-1"></i> Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>

<style>
/* ── Context Banner ── */
.kpe-context-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-left: 4px solid var(--kd-danger);
    border-radius: 12px;
    padding: 16px 20px;
}
.kpe-context-icon {
    width: 42px; height: 42px;
    background: var(--kd-danger-dim);
    border: 1.5px solid var(--kd-danger);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--kd-danger);
    font-size: 18px;
    flex-shrink: 0;
}
.kpe-context-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--kd-text-muted);
    margin-bottom: 2px;
}
.kpe-context-name {
    font-size: 15px; font-weight: 700;
    color: var(--kd-text-primary);
}
.kpe-context-nominal {
    font-size: 15px; font-weight: 800;
    color: var(--kd-danger);
}

/* ── Form Wrap ── */
.kpe-form-wrap {
    background: var(--kd-bg-surface);
    border: 1.5px solid var(--kd-border);
    border-radius: 16px;
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ── Field ── */
.kpe-field { display: flex; flex-direction: column; gap: 8px; }
.kpe-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--kd-text-muted);
    display: flex; align-items: center; gap: 2px;
}
.kpe-optional {
    font-size: 10px; font-weight: 500;
    background: var(--kd-bg-elevated);
    border: 1px solid var(--kd-border);
    border-radius: 4px;
    padding: 1px 5px;
    color: var(--kd-text-muted);
    margin-left: 4px;
    text-transform: none; letter-spacing: 0;
}

/* ── Input ── */
.kpe-input {
    width: 100%;
    background: var(--kd-bg-elevated);
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    color: var(--kd-text-primary);
    padding: 11px 14px;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.kpe-input:focus {
    border-color: var(--kd-accent);
    box-shadow: 0 0 0 3px #6282ed18;
}
.kpe-input--error { border-color: var(--kd-danger) !important; }
.kpe-input::placeholder { color: var(--kd-text-muted); }
input[type="date"].kpe-input::-webkit-calendar-picker-indicator { filter: invert(.4); }

.kpe-input-wrap { position: relative; }
.kpe-prefix {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%);
    font-size: 13px; font-weight: 700;
    color: var(--kd-text-muted);
}
.kpe-input--prefix { padding-left: 34px; }

.kpe-error { font-size: 12px; color: var(--kd-danger); }

/* ── Live Preview ── */
.kpe-preview {
    background: var(--kd-danger-dim);
    border: 1.5px solid var(--kd-danger);
    border-radius: 10px;
    padding: 12px 16px;
    animation: kwFadeIn .2s ease;
}
.kpe-preview__label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--kd-danger);
    margin-bottom: 6px;
}
.kpe-preview__name { font-size: 14px; font-weight: 600; color: var(--kd-text-primary); }
.kpe-preview__amount { font-size: 18px; font-weight: 800; color: var(--kd-danger); }
@keyframes kwFadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }

/* ── Actions ── */
.kpe-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    padding-top: 4px;
    border-top: 1px solid var(--kd-border);
}
.kpe-btn-ghost {
    background: none;
    border: 1.5px solid var(--kd-border);
    border-radius: 10px;
    color: var(--kd-text-muted);
    padding: 9px 20px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: all .15s;
}
.kpe-btn-ghost:hover { border-color: var(--kd-border-strong); color: var(--kd-text-primary); }
.kpe-btn-primary {
    background: var(--kd-danger);
    border: none; border-radius: 10px;
    color: #fff;
    padding: 10px 24px;
    font-size: 13px; font-weight: 700;
    cursor: pointer; font-family: inherit;
    transition: opacity .15s, transform .12s;
    box-shadow: 0 4px 16px #ff2f5530;
}
.kpe-btn-primary:hover { opacity: .85; transform: translateY(-1px); }
</style>

<script>
const namaInput    = document.getElementById('nama_pengeluaran');
const nominalInput = document.getElementById('nominal_keluar');
const preview      = document.getElementById('kpePreview');
const prevNama     = document.getElementById('prevNama');
const prevNominal  = document.getElementById('prevNominal');

function updatePreview() {
    const nama    = namaInput.value.trim();
    const nominal = parseInt(nominalInput.value) || 0;
    if (nama || nominal > 0) {
        preview.style.display = '';
        prevNama.textContent    = nama || '—';
        prevNominal.textContent = nominal > 0
            ? '-Rp ' + nominal.toLocaleString('id-ID')
            : '—';
    } else {
        preview.style.display = 'none';
    }
}
namaInput.addEventListener('input', updatePreview);
nominalInput.addEventListener('input', updatePreview);
</script>
</x-app-layout>
