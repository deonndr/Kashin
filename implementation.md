# ⚙️ Implementation Plan — Kashin

Dokumen ini berisi rencana teknis pengembangan fitur-fitur aplikasi Kashin secara bertahap.

---

## 📦 Fase 1: Setup Autentikasi & Role (Fondasi)

### 1A. Instalasi Laravel Breeze (Auth Scaffolding)
> Laravel Breeze adalah starter kit resmi Laravel untuk autentikasi. Ia menghasilkan sistem Login, Register, dan proteksi halaman secara otomatis.

**Perintah:**
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
```

**Yang dihasilkan otomatis oleh Breeze:**
*   Halaman `/login` dan `/register`
*   Middleware `auth` (proteksi halaman dari user yang belum login)
*   Tabel `users` sudah ada di migration bawaan Laravel

---

### 1B. Tambah Kolom `role` ke Tabel `users`
> Karena tabel `users` sudah pernah dimigrasikan, kita harus membuat **migration baru** (bukan mengedit yang lama).

**Perintah:**
```bash
php artisan make:migration add_role_to_users_table --table=users
```

**Isi migration:**
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Tambah kolom role dengan nilai default 'siswa'
        $table->enum('role', ['bendahara', 'siswa'])->default('siswa')->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
```

---

### 1C. Tambah Kolom `user_id` ke Tabel `siswa`
> Untuk menghubungkan akun login (`users`) dengan data siswa (`siswa`), diperlukan foreign key.

**Perintah:**
```bash
php artisan make:migration add_user_id_to_siswa_table --table=siswa
```

**Isi migration:**
```php
public function up(): void
{
    Schema::table('siswa', function (Blueprint $table) {
        // nullable() karena data siswa bisa ada sebelum punya akun login
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('id');
    });
}
```

---

### 1D. Buat Middleware Role
> Middleware adalah "penjaga pintu" yang memeriksa kondisi sebelum request diteruskan ke controller. Kita akan membuat middleware khusus untuk mengecek role pengguna.

**Perintah:**
```bash
php artisan make:middleware RoleMiddleware
```

**Isi `app/Http/Middleware/RoleMiddleware.php`:**
```php
public function handle(Request $request, Closure $next, string $role): Response
{
    // Cek apakah user sudah login DAN rolenya sesuai
    if (!auth()->check() || auth()->user()->role !== $role) {
        abort(403, 'Akses ditolak.'); // Kembalikan error 403
    }
    return $next($request);
}
```

**Daftarkan di `bootstrap/app.php`:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

---

### 1E. Proteksi Route Berdasarkan Role
> Update `routes/web.php` untuk membagi route berdasarkan siapa yang boleh mengaksesnya.

```php
// Route khusus Bendahara
Route::middleware(['auth', 'role:bendahara'])->group(function () {
    Route::resource('siswa', SiswaController::class);
    Route::resource('periode-iuran', PeriodeIuranController::class);
    Route::resource('pembayaran-iuran', PembayaranIuranController::class);
    Route::resource('kegiatan', KegiatanController::class);
    // ... dst
});

// Route khusus Siswa
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index']);
});
```

---

## 📦 Fase 2: CRUD Lengkap (Semua Modul)

Urutan pengerjaan CRUD berdasarkan ketergantungan data:

| Urutan | Modul | Controller | Alasan |
| :---: | :--- | :--- | :--- |
| 1 | Siswa | `SiswaController` | Data master, tidak bergantung ke tabel lain |
| 2 | Periode Iuran | `PeriodeIuranController` | Data master, tidak bergantung ke tabel lain |
| 3 | Kegiatan | `KegiatanController` | Data master, tidak bergantung ke tabel lain |
| 4 | Pembayaran Iuran | `PembayaranIuranController` | Butuh data Siswa & Periode Iuran sudah ada |
| 5 | Pengeluaran | `PengeluaranController` | Butuh data Kegiatan sudah ada |

---

## 📦 Fase 3: Dashboard & Laporan

### Dashboard Bendahara
Menggunakan Eloquent aggregation untuk menghitung statistik:
```php
// Contoh query untuk dashboard
$totalPemasukan = PembayaranIuran::sum('jumlah_bayar');
$totalPengeluaran = Pengeluaran::sum('nominal_keluar');
$saldoKas = $totalPemasukan - $totalPengeluaran;
$belumBayar = PembayaranIuran::where('status_bayar', 'Belum Lunas')->count();
```

### Dashboard Siswa
Filter data berdasarkan siswa yang sedang login:
```php
// Ambil data siswa yang terhubung ke akun yang sedang login
$siswa = auth()->user()->siswa; // via relasi di Model User
$riwayatBayar = $siswa->pembayaranIurans()->with('periodeIuran')->get();
```

---

## 📦 Fase 4: UX Polish

*   **Blade Layout Utama:** Buat `resources/views/layouts/app.blade.php` sebagai template induk dengan Navbar dan Sidebar Bootstrap. Semua halaman menggunakan `@extends('layouts.app')`.
*   **Flash Message:** Setiap redirect setelah aksi CRUD menyertakan session flash untuk pesan sukses/error. Ditampilkan di layout utama menggunakan Bootstrap Alert.
*   **Form Validation:** Setiap `store()` dan `update()` di controller wajib memvalidasi data sebelum disimpan ke database menggunakan `$request->validate([...])`.

---

## ❓ Open Questions

1.  Apakah **Siswa bisa Register sendiri**, atau hanya Bendahara yang mendaftarkan akun siswa?
2.  Apakah saat Bendahara membuat **Periode Iuran baru**, sistem otomatis membuat tagihan untuk semua siswa (status: Belum Lunas)? Atau cukup dicatat manual saat ada yang bayar?
