<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\PembayaranIuran;
use App\Models\Pengeluaran;
use App\Models\PeriodeIuran;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Bendahara ───
        $bendahara = User::create([
            'name'     => 'Bendahara XI RPL',
            'email'    => 'bendahara@kashin.com',
            'password' => Hash::make('bendahara'),
            'role'     => 'bendahara',
        ]);

        // ─── 2. Periode Iuran ───
        $periodes = [
            ['nama_periode' => 'Mei 2026',  'nominal_tagihan' => 50000, 'bulan' => 5,  'tahun' => 2026],
            ['nama_periode' => 'Juni 2026', 'nominal_tagihan' => 50000, 'bulan' => 6,  'tahun' => 2026],
        ];

        $periodeObjects = [];
        foreach ($periodes as $p) {
            $periodeObjects[] = PeriodeIuran::create($p);
        }

        // ─── 3. Kegiatan ───
        $kegiatanData = [
            ['nama_kegiatan' => 'Study Tour',  'estimasi_biaya' => 2000000],
            ['nama_kegiatan' => 'Perpisahan',  'estimasi_biaya' => 1500000],
            ['nama_kegiatan' => 'ATK Kelas',   'estimasi_biaya' => 500000],
        ];

        $kegiatanObjects = [];
        foreach ($kegiatanData as $k) {
            $kegiatanObjects[] = Kegiatan::create($k);
        }

        // ─── 4. Pengeluaran per kegiatan ───
        // Study Tour: 60% estimasi = 1.200.000
        Pengeluaran::create([
            'kegiatan_id'      => $kegiatanObjects[0]->id,
            'nama_pengeluaran' => 'DP Bus Pariwisata',
            'nominal_keluar'   => 800000,
            'tanggal_keluar'   => '2026-05-15',
        ]);
        Pengeluaran::create([
            'kegiatan_id'      => $kegiatanObjects[0]->id,
            'nama_pengeluaran' => 'Tiket Masuk Wisata',
            'nominal_keluar'   => 400000,
            'tanggal_keluar'   => '2026-05-20',
        ]);

        // Perpisahan: 35% estimasi = 525.000
        Pengeluaran::create([
            'kegiatan_id'      => $kegiatanObjects[1]->id,
            'nama_pengeluaran' => 'Sewa Gedung',
            'nominal_keluar'   => 525000,
            'tanggal_keluar'   => '2026-06-01',
        ]);

        // ATK Kelas: 40% estimasi = 200.000
        Pengeluaran::create([
            'kegiatan_id'      => $kegiatanObjects[2]->id,
            'nama_pengeluaran' => 'Pembelian ATK Kelas',
            'nominal_keluar'   => 120000,
            'tanggal_keluar'   => '2026-06-09',
        ]);
        Pengeluaran::create([
            'kegiatan_id'      => $kegiatanObjects[2]->id,
            'nama_pengeluaran' => 'Snack Rapat OSIS',
            'nominal_keluar'   => 200000,
            'tanggal_keluar'   => '2026-06-07',
        ]);

        // ─── 5. Data 32 Siswa ───
        $namaList = [
            'Andi Firmansyah', 'Siti Rahayu', 'Budi Santoso', 'Dewi Kurniawati',
            'Eko Prasetyo', 'Rizky Dwi P.', 'Nur Fadhilah', 'Yoga Pratama',
            'Lestari S.', 'M. Habib', 'Ahmad Fauzi', 'Rini Wulandari',
            'Hendri Saputra', 'Maya Sari', 'Dian Pratiwi', 'Fajar Nugroho',
            'Indra Gunawan', 'Laila Nuraini', 'Krisna Bayu', 'Nadia Putri',
            'Oktavian R.', 'Putri Amalia', 'Qori Ramadhani', 'Ridho Maulana',
            'Sandra Dewi', 'Teguh Wahyudi', 'Umar Hakim', 'Vina Septiani',
            'Wahyu Hidayat', 'Xena Puspita', 'Yusuf Anshori', 'Zahrani Fitri',
        ];

        // Siswa yang sudah lunas (27 orang) — index 0..26
        // Siswa belum bayar bulan ini (5 orang) — index 27..31 (Rizky dst)
        $belumBayarNama = ['Rizky Dwi P.', 'Nur Fadhilah', 'Yoga Pratama', 'Lestari S.', 'M. Habib'];

        $siswaObjects = [];
        foreach ($namaList as $idx => $nama) {
            $user = User::create([
                'name'     => $nama,
                'email'    => strtolower(str_replace([' ', '.'], ['_', ''], $nama)) . '@siswa.dev',
                'password' => Hash::make('siswa123'),
                'role'     => 'siswa',
            ]);

            $siswa = Siswa::create([
                'user_id'    => $user->id,
                'nisn'       => str_pad((string)($idx + 1000000001), 10, '0', STR_PAD_LEFT),
                'nama_siswa' => $nama,
                'kelas'      => 'XI RPL',
            ]);

            $siswaObjects[] = $siswa;
        }

        // ─── 6. Pembayaran Iuran ───
        // Semua 32 siswa bayar Mei (periode 0) — LUNAS
        foreach ($siswaObjects as $siswa) {
            PembayaranIuran::create([
                'siswa_id'         => $siswa->id,
                'periode_iuran_id' => $periodeObjects[0]->id,
                'jumlah_bayar'     => 50000,
                'tanggal_bayar'    => Carbon::create(2026, 5, rand(1, 28))->toDateString(),
                'status_bayar'     => 'Lunas',
            ]);
        }

        // Bulan Juni: 27 lunas, 5 belum bayar
        $belumBayarIds = collect($siswaObjects)
            ->filter(fn($s) => in_array($s->nama_siswa, $belumBayarNama))
            ->pluck('id')
            ->toArray();

        foreach ($siswaObjects as $siswa) {
            if (in_array($siswa->id, $belumBayarIds)) {
                continue; // skip — tidak ada record Juni
            }

            PembayaranIuran::create([
                'siswa_id'         => $siswa->id,
                'periode_iuran_id' => $periodeObjects[1]->id,
                'jumlah_bayar'     => 50000,
                'tanggal_bayar'    => Carbon::create(2026, 6, rand(1, 10))->toDateString(),
                'status_bayar'     => 'Lunas',
            ]);
        }
    }
}
