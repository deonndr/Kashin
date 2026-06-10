# Kisi-Kisi UJIKOM Kelas Industri — Kelas XI

---

## A. Ujian Praktek (Take-Home Project Backend & Database)

Siswa diwajibkan menyelesaikan project akhir berupa aplikasi berbasis Web murni (Real-Case) dengan ketentuan standar industri.

### 1. Ketentuan Spesifikasi

- **Tema Aplikasi Bebas (Real-Case)**
  Setiap siswa bebas merancang aplikasi dengan tema apapun (contoh: Sistem Kasir, Manajemen Stok, Aplikasi Presensi, Sistem Perpustakaan, Rental Kendaraan, Manajemen Tugas, dll.) asalkan memiliki fungsi yang jelas dan logis.

- **Pra-Ujikom & Setor Detail (H-7)**
  Satu minggu sebelum ujian, mentor akan memberikan arahan project. Siswa wajib menyetorkan judul aplikasi beserta daftar tabel database maksimal 1 hari sejak diumumkan.
  - Tema/judul tidak boleh sama dalam satu kelas (siapa cepat dia dapat)
  - Dikunci di grup koordinasi dan tidak boleh berubah hingga hari-H
  - **Format Penyetoran:** Nama Siswa – Judul Aplikasi UMKM – List 5 Tabel Database

- **Fungsionalitas CRUD**
  Aplikasi wajib mengimplementasikan sistem CRUD (Create, Read, Update, Delete) secara lengkap dan berjalan 100% tanpa error fatal.

- **Struktur Database Relasional**
  Wajib merancang dan mengimplementasikan database (MySQL/phpMyAdmin) dengan minimal **5 tabel** yang saling terhubung secara logis.

### 2. Indikator Penilaian Teknis & Keterampilan

- **Kesesuaian Objek Uji (Matching)**
  Produk aplikasi dan susunan relasi tabel yang dipresentasikan pada hari-H wajib sesuai dengan judul dan list tabel yang sudah dikunci pada H-7.

- **Version Control (GitHub)**
  Siswa wajib mendemonstrasikan penggunaan Git dan GitHub sebagai bukti manajemen repositori source code secara terstruktur.

- **Penguasaan Source Code Mandiri**
  Siswa wajib memahami seluruh baris kode backend yang dibuat. Penguji akan menunjuk baris query (seperti INSERT atau SELECT JOIN) secara acak, dan siswa harus mampu menjelaskan alur logika datanya dari database hingga tampil ke frontend.

- **Kemampuan Live Coding**
  Siswa mampu melakukan modifikasi kode backend/frontend ringan secara langsung di depan penguji untuk membuktikan orisinalitas karya.

- **Kerapian UI/UX**
  Tampilan antarmuka aplikasi tertata rapi, user-friendly, dan layak digunakan untuk operasional skala industri/UMKM.

---

## B. Ujian Teori (GetSkill Platform)

Materi ujian teori berfokus pada analisis logika backend, arsitektur database relasional, dan penanganan manipulasi data tingkat lanjut.

---

### BAB 1 — Arsitektur & Konfigurasi Framework

- Menganalisis konsep dan alur kerja pola arsitektur **MVC (Model-View-Controller)**
- Memahami fungsi file konfigurasi lingkungan **(.env)** untuk proteksi kredensial database
- Memahami penggunaan perintah **Artisan CLI** untuk menjalankan server lokal dan membuat komponen sistem

---

### BAB 2 — Routing & Logic Handling (Controller & Blade)

- Menganalisis pendaftaran rute URL pada file **web.php** dan penanganan parameter dinamis
- Memahami pembuatan dan pemanggilan fungsi pada **Resource Controller**
- Menganalisis penggunaan direktif **Blade Templating** (`@extends`, `@yield`, `@include`, `@foreach`, `@if`) untuk pewarisan layout halaman frontend

---

### BAB 3 — Database, Migration, & Eloquent ORM

- Memahami pengelolaan skema dan struktur tabel database menggunakan **Migration** (perintah `migrate`, `migrate:fresh`, `rollback`)
- Menganalisis operasi manipulasi data (CRUD) menggunakan **Eloquent ORM** (`all()`, `find()`, `create()`, `save()`, `update()`, `delete()`)
- Memahami pengisian data dummy otomatis menggunakan **Database Seeder**
- Menganalisis implementasi relasi antar-tabel menggunakan Eloquent (**One to Many** / `hasMany` dan `belongsTo`)

---

### BAB 4 — Keamanan Sistem & Validasi Input (Security)

- Menganalisis pencegahan celah keamanan dari serangan **SQL Injection**
- Memahami fungsi dan penerapan token **@csrf** (Cross-Site Request Forgery) pada setiap form input
- Menganalisis teknik enkripsi/hashing password menggunakan **`Hash::make()`**
- Memahami implementasi aturan **validasi input data** (validation rules) untuk menjaga validitas data sebelum masuk database

---

### BAB 5 — Middleware & Request Lifecycle

- Memahami fungsi **Middleware** sebagai penyaring (filter) request HTTP yang masuk ke dalam sistem aplikasi
- Menganalisis implementasi **middleware auth** untuk membatasi hak akses halaman login dan hak akses pengguna
- Menganalisis alur perpindahan data (data flow) secara utuh: request frontend → diproses controller → disimpan ke database → dikembalikan sebagai response