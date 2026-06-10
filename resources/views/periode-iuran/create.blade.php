<x-app-layout>
    <x-slot name="title">Tambah Periode Iuran</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Tambah Periode Iuran</h2>
            <p class="text-muted small mb-0">Buat periode penarikan kas baru</p>
        </div>
        <a href="{{ route('periode-iuran.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card kas-card" style="max-width: 540px;">
        <form method="POST" action="{{ route('periode-iuran.store') }}">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted text-uppercase" for="bulan">Bulan</label>
                    <select id="bulan" name="bulan" class="form-select @error('bulan') is-invalid @enderror">
                        <option value="" disabled selected>Pilih Bulan</option>
                        @foreach([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ] as $num => $nama)
                            <option value="{{ $num }}" {{ old('bulan') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('bulan') 
                        <div class="invalid-feedback small">{{ $message }}</div> 
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted text-uppercase" for="tahun">Tahun</label>
                    <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                           value="{{ old('tahun', date('Y')) }}" placeholder="{{ date('Y') }}" min="2020" max="2099">
                    @error('tahun') 
                        <div class="invalid-feedback small">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="nominal_tagihan">Nominal Tagihan (Rp)</label>
                <input type="number" id="nominal_tagihan" name="nominal_tagihan" class="form-control @error('nominal_tagihan') is-invalid @enderror"
                       value="{{ old('nominal_tagihan') }}" placeholder="50000" min="1000">
                @error('nominal_tagihan') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Periode</button>
                <a href="{{ route('periode-iuran.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
