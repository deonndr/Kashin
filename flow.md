# 📖 Panduan Presentasi & Uji Kompetensi Keahlian (UKK) — Kashin

Dokumen ini berisi rangkuman alur kerja (flow) aplikasi, kesesuaian kisi-kisi, daftar pertanyaan penguji yang sering muncul beserta jawaban mantap, serta panduan skenario live coding untuk membantu persiapan ujian.

---

## 🚀 Bagian 1: Alur Kerja (Flow) Aplikasi per Role

Aplikasi **Kashin** membagi hak akses ke dalam **2 Role** menggunakan Middleware otorisasi yang ketat:

### 1. Role: Bendahara (Write-Access)
*   **Login & Dashboard:** Setelah berhasil login, Bendahara diarahkan ke `/dashboard`. Halaman ini berisi rangkuman statistik keuangan: total kas masuk, kas keluar, sisa saldo kas saat ini, daftar **seluruh** siswa yang belum lunas bulan ini (tanpa limit), serta 5 transaksi terbaru (gabungan kas masuk & keluar).
*   **Manajemen Siswa (CRUD):** 
    *   **Create (Tambah):** Bendahara menginput NISN, Nama, Kelas, dan Email. Sistem secara otomatis membuat data di tabel `siswa` sekaligus akun login di tabel `users` dengan password default `siswa123`.
    *   **Update (Edit):** Bendahara dapat mengedit data siswa. Jika nama siswa diubah, sistem otomatis menyinkronkan nama pada akun `users` terkait agar konsisten di semua tampilan.
    *   **Delete (Hapus):** Menghapus data siswa otomatis menghapus akun `users` miliknya (menghindari akun zombie/tanpa data).
*   **Manajemen Periode Iuran:** Mengatur target nominal iuran bulanan. Input didesain menggunakan dropdown Bulan dan angka Tahun untuk menghindari kesalahan penulisan, serta dilindungi validasi agar nama periode tidak duplikat.
*   **Manajemen Pembayaran Iuran:** Mencatat pembayaran iuran siswa. Bendahara cukup memilih nama siswa dan periode iuran. Sistem secara otomatis menolak pencatatan jika kombinasi siswa dan periode tersebut sudah pernah dilunasi.
*   **Kegiatan & Pengeluaran Kas:** Bendahara dapat mencatat rencana kegiatan kelas beserta estimasi anggaran. Ketika dana dicairkan, Bendahara mencatat detail pengeluaran riil per kegiatan yang otomatis memotong total saldo kas kelas.

### 2. Role: Siswa (Akses Transparansi / Read-Only)
*   **Login & Dashboard Siswa:** Siswa login langsung diarahkan ke `/dashboard-siswa`. Halaman ini dirancang personal dan informatif:
    *   **Status Iuran Bulan Ini:** Menampilkan badge status apakah iuran dirinya di bulan berjalan sudah **Lunas** (warna hijau) atau **Belum Bayar** (warna merah).
    *   **Histori Pembayaran Pribadi:** Daftar lengkap seluruh transaksi pembayaran yang pernah ia lakukan secara kronologis.
*   **Laporan Transparansi Kegiatan:** Halaman khusus yang menampilkan rincian kegiatan kelas beserta daftar pengeluaran riil lengkap dengan nominal dan tanggal pemakaian dana demi keterbukaan keuangan kelas.

---

## 🎓 Bagian 2: Pertanyaan Penguji & Jawaban Mantap

Berikut adalah prediksi pertanyaan yang paling sering diajukan oleh penguji eksternal/industri beserta cara menjawabnya secara profesional:

### ❓ Pertanyaan 1: "Bagaimana cara membatasi akses halaman Bendahara agar tidak bisa dibuka oleh Siswa?"
*   **Jawaban Mantap:**
    > "Saya mengelompokkan rute di file `routes/web.php` ke dalam Route Group yang dilindungi oleh middleware `auth` bawaan Laravel dan middleware kustom bernama `RoleMiddleware`. Di dalam middleware tersebut, sistem akan menyaring role user yang sedang login. Jika role-nya tidak cocok dengan parameter rute (misalnya siswa mencoba membuka dashboard bendahara), middleware akan otomatis mengarahkan user kembali ke dashboard rolenya masing-masing dengan pesan penolakan yang ramah menggunakan SweetAlert2."
*   **Referensi File:** [RoleMiddleware.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Http/Middleware/RoleMiddleware.php)

### ❓ Pertanyaan 2: "Jelaskan bagaimana struktur relasi database di aplikasi ini!"
*   **Jawaban Mantap:**
    > "Struktur database aplikasi Kashin dirancang secara relasional. Tabel `users` memiliki relasi **One-to-One** (`hasOne` / `belongsTo`) dengan tabel `siswa` melalui kolom `user_id`. Tabel `siswa` berelasi **One-to-Many** (`hasMany` / `belongsTo`) dengan tabel `pembayaran_iuran`. Begitu pula dengan tabel `periode_iuran` yang memiliki relasi **One-to-Many** dengan `pembayaran_iuran`. Untuk pencatatan dana keluar, tabel `kegiatan` memiliki relasi **One-to-Many** dengan tabel `pengeluaran`."
*   **Referensi File:** Model [Siswa.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Models/Siswa.php) dan [User.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Models/User.php)

### ❓ Pertanyaan 3: "Bagaimana cara kamu menjamin tidak ada pencatatan pembayaran ganda untuk siswa di periode yang sama?"
*   **Jawaban Mantap:**
    > "Saya menerapkan proteksi ganda. Pertama, di level aplikasi (Controller), saya membuat aturan validasi unik yang memvalidasi kombinasi `siswa_id` dan `periode_iuran_id` sebelum data disimpan. Kedua, di level database, saya membuat migrasi untuk menambahkan `unique constraint` gabungan pada kedua kolom tersebut pada tabel `pembayaran_iuran` untuk mengunci integritas data secara permanen."
*   **Referensi File:** [PembayaranIuranController.php:L40-L45](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Http/Controllers/PembayaranIuranController.php#L40-L45) dan file migrasi [add_unique_to_pembayaran_iuran_table.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/database/migrations/2026_06_11_000001_add_unique_to_pembayaran_iuran_table.php)

### ❓ Pertanyaan 4: "Apa fungsi penulisan `@csrf` pada setiap tag `<form>` di Blade?"
*   **Jawaban Mantap:**
    > "`@csrf` digunakan untuk menyisipkan token keamanan Cross-Site Request Forgery. Token ini memastikan bahwa request POST/PUT/DELETE yang dikirimkan ke aplikasi benar-benar berasal dari pengguna terautentikasi di aplikasi kita, bukan dari situs luar yang mencoba menyerang. Jika token ini tidak ada atau tidak valid, Laravel akan otomatis menolak request dengan status error 419 (Page Expired)."

---

## 💻 Bagian 3: Prediksi Skenario Live Coding Ujian

Berikut adalah skenario live coding ringan yang biasa diminta penguji untuk membuktikan keaslian kodinganmu:

### 🛠️ Skenario A: Mengubah Teks Penjelasan/Label pada View
*   **Tugas:** *"Ganti teks info saat data kosong di dashboard siswa."*
*   **Solusi:** Buka [dashboard-siswa.blade.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/resources/views/dashboard-siswa.blade.php#L114-L118), cari blok `@forelse` paling bawah, dan ubah teks di dalam div class `text-center py-5` sesuai instruksi penguji.

### 🛠️ Skenario B: Mengubah Batasan Validasi Input
*   **Tugas:** *"Ubah nominal minimal iuran yang boleh didaftarkan dari Rp 1.000 menjadi Rp 10.000."*
*   **Solusi:** Buka [PeriodeIuranController.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Http/Controllers/PeriodeIuranController.php#L30), lalu ubah baris validasi `nominal_tagihan` menjadi:
    ```php
    'nominal_tagihan' => 'required|integer|min:10000',
    ```

### 🛠️ Skenario C: Mengubah Urutan Data (Sorting Query)
*   **Tugas:** *"Ubah urutan histori pembayaran siswa di dashboardnya dari yang terlama ke yang paling baru."*
*   **Solusi:** Buka [SiswaDashboardController.php](file:///c:/XI-FILE/HUMMATECH/UKK/Kashin/app/Http/Controllers/SiswaDashboardController.php#L31-L34), ubah method query:
    ```php
    // Ganti ->latest('tanggal_bayar') menjadi:
    ->oldest('tanggal_bayar')
    ```
