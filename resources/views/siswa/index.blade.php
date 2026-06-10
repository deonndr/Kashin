<x-app-layout>
    <x-slot name="title">Data Siswa</x-slot>

    <div class="page-header">
        <div>
            <h2 class="fw-bold text-white mb-0">Data Siswa</h2>
            <p class="text-muted small mb-0">Total {{ $siswas->count() }} siswa terdaftar</p>
        </div>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-person-plus"></i> Tambah Siswa
        </a>
    </div>

    <div class="card kas-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">#</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">NISN</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Nama Siswa</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Kelas</th>
                        <th class="text-muted small fw-bold text-uppercase border-secondary-subtle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $i => $siswa)
                        <tr>
                            <td class="text-muted small border-secondary-subtle">{{ $i + 1 }}</td>
                            <td class="border-secondary-subtle">
                                <code class="bg-dark-subtle text-light-emphasis border border-secondary-subtle p-1 px-2 rounded small">{{ $siswa->nisn }}</code>
                            </td>
                            <td class="border-secondary-subtle">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $colors = ['primary', 'info', 'success', 'warning', 'danger', 'secondary'];
                                        $c = $colors[$i % count($colors)];
                                        $inisial = strtoupper(substr($siswa->nama_siswa, 0, 2));
                                    @endphp
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold bg-{{ $c }}-subtle text-{{ $c }} small" style="width: 32px; height: 32px;">
                                        {{ $inisial }}
                                    </div>
                                    <span class="text-light fw-semibold">{{ $siswa->nama_siswa }}</span>
                                </div>
                            </td>
                            <td class="border-secondary-subtle">
                                    <span class="kas-badge kas-badge-primary py-1 px-2" style="font-size: 11px !important;">{{ $siswa->kelas }}</span>
                                </td>
                                <td class="border-secondary-subtle">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('siswa.destroy', $siswa) }}"
                                              class="delete-form" data-message="Hapus siswa {{ $siswa->nama_siswa }}? Semua data pembayarannya ikut terhapus.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted border-secondary-subtle">
                                <i class="bi bi-people fs-1 mb-2 d-block"></i>
                                <div class="fw-semibold">Belum ada siswa</div>
                                <small>Klik "Tambah Siswa" untuk memulai pendaftaran.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
