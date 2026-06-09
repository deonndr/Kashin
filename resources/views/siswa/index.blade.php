<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Kashin</title>

    {{-- CDN Bootstrap 5 — dimuat langsung dari internet, tidak perlu install apapun --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    {{-- Header halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">Data Siswa</h1>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
            + Tambah Siswa
        </a>
    </div>

    {{-- Tabel data siswa --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswas as $index => $siswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $siswa->nisn }}</td>
                        <td>{{ $siswa->nama_siswa }}</td>
                        <td>{{ $siswa->kelas }}</td>
                        <td>
                            {{-- route() helper: menghasilkan URL dari nama route --}}
                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            {{-- DELETE butuh form + method spoofing karena HTML hanya support GET & POST --}}
                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus siswa ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
