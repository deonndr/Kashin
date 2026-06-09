# Panduan Teknis dan Rangkuman Kisi-Kisi Proyek UKK PPLG: Aplikasi Kas Digital

Dokumen ini berfungsi sebagai acuan teoretis, dokumentasi arsitektur sistem, dan panduan standardisasi kompetensi untuk proyek Uji Kompetensi Keahlian (UKK) jurusan Pengembangan Perangkat Lunak dan Gim (PPLG). Seluruh isi dokumen ini berfokus pada penjelasan konsep, poin penting penilaian, dan struktur logis basis data tanpa melibatkan sintaksis pemrograman atau kode sumber.

---

## 1. Penjelasan Kisi-Kisi dan Poin Penting Penilaian UKK

Uji Kompetensi Keahlian (UKK) untuk jurusan PPLG dirancang untuk mengukur dua aspek kompetensi utama peserta didik, yaitu kesiapan praktik operasional di lapangan serta pemahaman teoretis yang kuat.

### A. Aspek Evaluasi Praktik dan Live Coding
* **Penguasaan Logika Mandiri (*Source Code Mastery*):** Peserta ujian dituntut untuk memahami secara utuh alur logika dari aplikasi yang dibuat. Penguji akan memverifikasi otentisitas karya melalui sesi tanya jawab mendalam dan instruksi modifikasi logika secara langsung (*Live Coding*) di tempat ujian.
* **Konsistensi Struktur Data:** Struktur dan nama tabel yang telah didaftarkan pada saat Pra-UKK bersifat mengikat dan tidak boleh diubah. Aplikasi harus mendemonstrasikan hubungan antar-tabel yang stabil, konsisten, dan bebas dari kesalahan fatal (*zero-error*).
* **Rekam Jejak Digital (*Version Control System*):** Proses pengerjaan proyek harus terdokumentasi secara berkala menggunakan Git dan diunggah ke GitHub. Peserta dinilai berdasarkan riwayat pembaruan (*commit messages*) yang terstruktur, yang membuktikan bahwa aplikasi dibangun secara bertahap dan mandiri.
* **Kombinasi UI/UX yang Ergonomis:** Antarmuka aplikasi harus memenuhi standar kenyamanan pengguna, memiliki tata letak yang rapi, serta responsif saat diakses dari berbagai perangkat (komputer, tablet, maupun ponsel).

### B. Aspek Evaluasi Teori (Platform GetSkill)
* **Siklus Hidup Permintaan Web (*HTTP Request Lifecycle*):** Pemahaman mengenai bagaimana sebuah data atau permintaan dari peramban diproses oleh sistem, mulai dari pemetaan jalur (Routing), pengolahan logika (Controller), manipulasi data pada basis data (Model), hingga ditampilkan kembali kepada pengguna (View).
* **Keamanan Informasi Aplikasi (*Application Security*):** Pemahaman mengenai proteksi dasar web, seperti penggunaan token khusus untuk mencegah pemalsuan permintaan dari sisi luar (*Cross-Site Request Forgery*) dan pencegahan penyusupan instruksi database berbahaya (*SQL Injection*).
* **Gerbang Keamanan Jalur (*Middleware*):** Pemahaman fungsi mekanisme penyaringan akses untuk membatasi halaman-halaman tertentu berdasarkan hak akses pengguna yang sah (misalnya membatasi hak akses halaman bendahara agar tidak bisa dibuka oleh siswa biasa).

---

## 2. Klasifikasi Arsitektur Basis Data (Hubungan Tabel Parent dan Child)

Sistem Kas Digital ini dibangun di atas 5 tabel utama yang diklasifikasikan ke dalam dua kasta hubungan, yaitu Tabel Master (Parent) dan Tabel Transaksi (Child). Pemisahan ini dilakukan untuk menjaga hubungan logis dan validasi laporan keuangan.

### A. Tabel Master / Tabel Parent (Entitas Mandiri)
Tabel Master atau *Parent Table* adalah tabel induk yang menyimpan data utama yang bersifat statis atau jarang berubah. Tabel-tabel ini berdiri sendiri dan tidak bergantung pada keberadaan data di tabel lain.

1. **Tabel `siswa`**
   * **Peran:** Menyimpan data identitas resmi seluruh murid yang menjadi subjek atau anggota penarikan kas kelas.
   * **Poin Penting:** Meng   gunakan nomor identitas unik yang tidak boleh kembar sebagai pencatat utama identitas murid.
2. **Tabel `periode_iuran`**
   * **Peran:** Menyimpan parameter pengaturan waktu penarikan uang kas (misalnya batasan bulanan atau mingguan) beserta nominal uang yang wajib dibayarkan pada periode tersebut.
   * **Poin Penting:** Menjadi acuan dasar bagi sistem untuk menentukan apakah seorang murid sudah melunasi kewajiban anggarannya atau belum pada waktu tertentu.
3. **Tabel `kegiatan`**
   * **Peran:** Menyimpan daftar rencana agenda, program kerja, atau kebutuhan operasional internal organisasi/kelas yang membutuhkan pembiayaan dari uang kas.

### B. Tabel Transaksi / Tabel Child (Entitas Terikat)
Tabel Transaksi atau *Child Table* adalah tabel anak yang bertugas mencatat aktivitas atau kejadian dinamis yang berubah setiap waktu. Tabel ini tidak dapat berdiri sendiri karena setiap baris datanya wajib menumpang atau merujuk pada nomor identitas (*ID*) dari Tabel Parent.

1. **Tabel `pembayaran_iuran` (Anak dari Tabel `siswa` dan `periode_iuran`)**
   * **Peran:** Mencatat setiap kejadian uang kas masuk yang dibayarkan oleh murid.
   * **Mekanisme Hubungan:** Tabel ini menarik identitas dari tabel `siswa` untuk mencatat *siapa yang membayar*, dan menarik identitas dari tabel `periode_iuran` untuk mencatat *untuk bulan atau minggu apa uang tersebut ditujukan*.
2. **Tabel `pengeluaran` (Anak dari Tabel `kegiatan`)**
   * **Peran:** Mencatat setiap detail realisasi uang kas yang keluar untuk membiayai kebutuhan tertentu.
   * **Mekanisme Hubungan:** Tabel ini menarik identitas dari tabel `kegiatan` untuk menegaskan *untuk agenda atau program kerja apa uang kas tersebut dibelanjakan*.

---

## 3. Aturan Penghapusan Berantai (*Cascade Delete*)

Hubungan antara Tabel Parent dan Tabel Child diikat oleh aturan proteksi yang ketat di tingkat basis data. Sistem ini menerapkan metode penghapusan berantai (*On Delete Cascade*) demi menjaga kestabilan data.

* **Prinsip Kerja:** Jika sebuah data pada Tabel Parent (Induk) dihapus, maka secara otomatis seluruh data yang berkaitan di Tabel Child (Anak) akan ikut terhapus oleh sistem basis data secara permanen.
* **Contoh Kasus:** Jika data seorang siswa pada tabel `siswa` dihapus karena pindah sekolah, maka seluruh riwayat catatan pembayaran uang kas milik siswa tersebut pada tabel `pembayaran_iuran` akan otomatis ikut dibersihkan oleh sistem. Hal ini bertujuan untuk mencegah adanya data sampah atau data yatim piatu (*orphan data*) yang tidak memiliki acuan induk di dalam database.

---

## 4. Alur Bisnis Utama Aplikasi (*Core Business Workflow*)

Secara fungsional, aplikasi Kas Digital harus mampu menjalankan tiga sirkulasi data keuangan secara akurat dan akuntabel:

1. **Otomatisasi Manajemen Data Master:** Menyediakan fungsi pengelolaan penuh (tambah, lihat, ubah, hapus) secara aman pada entitas komponen induk (`siswa`, `periode_iuran`, dan `kegiatan`).
2. **Pencatatan Aliran Keuangan Ganda:**
   * **Arus Masuk (Pemasukan):** Proses validasi uang masuk yang mencocokkan ketepatan jumlah setoran murid terhadap target nominal wajib pada periode yang dituju.
   * **Arus Keluar (Pengeluaran):** Proses pencatatan pengurangan dana kas yang wajib dilekatkan pada penanggung jawab agenda kegiatan yang sah pada tabel kegiatan.
3. **Kalkulasi Akuntansi Berjalan:** Logika otomatisasi untuk menyajikan laporan sisa saldo kas bersih secara instan kepada pengguna, yang diperoleh dari hasil operasi matematika dasar:
   
   **Sisa Saldo = Total Pemasukan - Total Pengeluaran**