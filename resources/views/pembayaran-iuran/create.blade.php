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

    <div class="card kas-card" style="max-width: 560px;">
        <form method="POST" action="{{ route('pembayaran-iuran.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="siswa_id">Siswa</label>
                <select id="siswa_id" name="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} — {{ $s->kelas }}
                        </option>
                    @endforeach
                </select>
                @error('siswa_id') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="periode_iuran_id">Periode Iuran</label>
                <select id="periode_iuran_id" name="periode_iuran_id" class="form-select @error('periode_iuran_id') is-invalid @enderror">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}" {{ old('periode_iuran_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_periode }} — Rp {{ number_format($p->nominal_tagihan, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('periode_iuran_id') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="jumlah_bayar">Jumlah Bayar (Rp)</label>
                <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror"
                       value="{{ old('jumlah_bayar') }}" placeholder="50000" min="1000">
                @error('jumlah_bayar') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="tanggal_bayar">Tanggal Bayar</label>
                <input type="date" id="tanggal_bayar" name="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror"
                       value="{{ old('tanggal_bayar', date('Y-m-d')) }}">
                @error('tanggal_bayar') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted text-uppercase" for="status_bayar">Status</label>
                <select id="status_bayar" name="status_bayar" class="form-select @error('status_bayar') is-invalid @enderror">
                    <option value="Lunas"       {{ old('status_bayar') === 'Lunas'       ? 'selected' : '' }}>Lunas</option>
                    <option value="Belum Lunas" {{ old('status_bayar') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
                @error('status_bayar') 
                    <div class="invalid-feedback small">{{ $message }}</div> 
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Pembayaran</button>
                <a href="{{ route('pembayaran-iuran.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
