# 🗺️ Flow App — Kashin (Manajemen Kas Digital)

Dokumen ini menggambarkan alur pengguna (user flow) untuk **2 role** yang ada di aplikasi Kashin.

---

## 👥 Role yang Ada

| Role | Deskripsi |
| :--- | :--- |
| 👑 **Bendahara** | Admin penuh. Bisa mengelola seluruh data dan transaksi. |
| 👤 **Siswa** | Viewer. Hanya bisa melihat status iuran & transparansi pengeluaran pribadi. |

---

## 🔐 Alur Masuk (Auth Flow)

```
[ Halaman Login ]
      |
      ├── Role: Bendahara ──────────> [ Dashboard Admin ]
      |
      └── Role: Siswa ──────────────> [ Dashboard Siswa ]
```

*   Satu halaman login untuk semua pengguna.
*   Setelah login, **Middleware** mendeteksi role dan mengarahkan ke dashboard yang sesuai.
*   Jika Siswa mencoba akses URL Bendahara → ditolak dengan **Error 403 Forbidden**.

---

## 👑 Flow: Bendahara (Admin)

### Dashboard Admin
> Halaman pertama setelah login. Menampilkan ringkasan keuangan real-time.

```
[ Dashboard Bendahara ]
  ├── 💵 Kartu: Total Saldo Kas
  ├── ✅ Kartu: Siswa Sudah Bayar (bulan ini)
  ├── ❌ Kartu: Siswa Belum Bayar (bulan ini)
  └── 📉 Kartu: Total Pengeluaran (bulan ini)
```

---

### Modul 1: Kelola Siswa
```
[ /siswa ] Daftar semua siswa
  ├── Tombol "Tambah Siswa" → [ /siswa/create ] Form input siswa baru
  ├── Tombol "Edit" per baris → [ /siswa/{id}/edit ] Form edit data siswa
  └── Tombol "Hapus" per baris → Konfirmasi → Data terhapus
```

---

### Modul 2: Kelola Periode Iuran
```
[ /periode-iuran ] Daftar semua periode iuran (misal: Juli 2026, Agustus 2026)
  ├── Tombol "Tambah Periode" → [ /periode-iuran/create ]
  |       └── Input: Nama Periode, Nominal Tagihan
  └── Tombol "Hapus" → Konfirmasi → Data terhapus
```

---

### Modul 3: Pembayaran Iuran (Transaksi Masuk)
```
[ /pembayaran-iuran ] Rekap semua pembayaran
  ├── Filter: Berdasarkan Periode | Status (Lunas/Belum Lunas) | Kelas
  ├── Tombol "Catat Pembayaran" → [ /pembayaran-iuran/create ]
  |       └── Input: Pilih Siswa, Pilih Periode, Nominal Bayar, Tanggal, Status
  └── Tombol "Hapus" per baris → Konfirmasi → Data terhapus
```

---

### Modul 4: Kelola Kegiatan & Pengeluaran (Transaksi Keluar)
```
[ /kegiatan ] Daftar semua kegiatan kelas
  ├── Tombol "Tambah Kegiatan" → [ /kegiatan/create ]
  |       └── Input: Nama Kegiatan, Estimasi Biaya
  |
  └── Tombol "Lihat Detail" per kegiatan → [ /kegiatan/{id} ]
        ├── Info: Estimasi Biaya vs Total Realisasi Pengeluaran
        ├── Tabel: Rincian Pengeluaran
        └── Tombol "Tambah Pengeluaran" → [ /kegiatan/{id}/pengeluaran/create ]
                └── Input: Nama Item, Nominal, Tanggal
```

---

## 👤 Flow: Siswa (Viewer)

### Dashboard Siswa
> Halaman pertama setelah login. Data yang tampil **hanya milik siswa yang login**.

```
[ Dashboard Siswa ]
  ├── Kartu: Status Iuran Bulan Ini (Lunas / Belum Lunas)
  ├── Tabel: Riwayat Semua Pembayaran Iuranku
  |       └── Kolom: Periode | Nominal | Status | Tanggal Bayar
  └── Tombol: "Lihat Laporan Kegiatan" → [ /laporan-kegiatan ] (Read-Only)
```

---

## 🗺️ Peta URL Lengkap (Sitemap)

```
/login                            → Halaman Login

-- BENDAHARA --
/dashboard                        → Dashboard Admin
/siswa                            → Daftar Siswa
/siswa/create                     → Tambah Siswa
/siswa/{id}/edit                  → Edit Siswa
/periode-iuran                    → Daftar Periode Iuran
/periode-iuran/create             → Tambah Periode
/pembayaran-iuran                 → Rekap Pembayaran
/pembayaran-iuran/create          → Catat Pembayaran Baru
/kegiatan                         → Daftar Kegiatan
/kegiatan/create                  → Tambah Kegiatan
/kegiatan/{id}                    → Detail + Pengeluaran per Kegiatan
/kegiatan/{id}/pengeluaran/create → Tambah Pengeluaran

-- SISWA --
/dashboard-siswa                  → Status Iuran Pribadi
/laporan-kegiatan                 → Transparansi Pengeluaran (Read-Only)
```
