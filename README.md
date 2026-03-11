# SIMA - Sistem Informasi Manajemen Asrama

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![SweetAlert2](https://img.shields.io/badge/SweetAlert2-FF4154?style=for-the-badge&logo=javascript&logoColor=white)

**SIMA** adalah aplikasi berbasis web yang dirancang untuk mendigitalisasi dan mempermudah pengelolaan operasional asrama. Sistem ini menjembatani interaksi antara pengelola (Admin) dan mahasiswa/penghuni asrama dalam hal pendaftaran, pembayaran, perizinan, hingga pelaporan kerusakan fasilitas.

---

## Fitur Utama

### Guest (Calon Penghuni)
* **Landing Page & Informasi Fasilitas:** Melihat informasi asrama dan kamar.
* **Pendaftaran Online:** Mendaftar asrama dengan mengunggah berkas persyaratan (KTP, KTM, dll).
* **Cek Status Seleksi:** Memantau apakah pendaftaran diterima, ditolak, atau masih diproses.
* **Pengajuan Survei:** Mengatur jadwal kunjungan/survei lokasi asrama sebelum mendaftar.

### Admin (Pengelola)
* **Dashboard Statistik:** Ringkasan data pendaftar, kapasitas kamar, dan laporan terkini.
* **Verifikasi Pendaftar:** Menyetujui atau menolak calon penghuni baru.
* **Manajemen Kamar & Kapasitas:** Mengatur alokasi kamar untuk penghuni (mendukung algoritma alokasi).
* **Verifikasi Pembayaran:** Memeriksa dan memvalidasi bukti transfer pembayaran sewa.
* **Kelola Laporan Kerusakan:** Menindaklanjuti keluhan fasilitas dari status 'Baru' ➡️ 'Diproses' ➡️ 'Selesai'.
* **Kelola Perizinan/Permohonan:** Menyetujui permohonan izin pulang kampung, pindah kamar, atau perpanjangan sewa.
* **Laporan & Rekapitulasi (Cetak PDF):** Menghasilkan dokumen laporan otomatis terkait kapasitas kamar, keuangan, dan perbaikan.

### Penghuni (User Aktif)
* **Dashboard Personal:** Menampilkan informasi kamar, teman sekamar, dan tagihan bulan ini.
* **Modul Pembayaran:** Mengunggah bukti transfer pembayaran sewa (sistem mendeteksi masa aktif 1 tahun).
* **Lapor Kerusakan:** Melaporkan fasilitas rusak dengan melampirkan foto bukti.
* **Pengajuan Permohonan:** Meminta izin secara administratif (pindah kamar, perpanjangan sewa).

---

## Teknologi yang Digunakan
* **Backend:** PHP (Native/Procedural)
* **Database:** MySQL
* **Frontend:** HTML5, CSS3, JavaScript
* **Framework CSS:** Bootstrap 5
* **Library Ekstra:** SweetAlert2 (Pop-up Alerts), Bootstrap Icons

---

## Persyaratan Sistem (Prerequisites)
Pastikan komputer/server Anda telah terinstall:
1. Web Server lokal seperti **XAMPP**, **WAMP**, atau **Laragon** (mendukung Apache & MySQL).
2. **PHP versi 7.4 atau 8.x**.

---

##  Cara Instalasi

1. **Clone atau Unduh Repository**
   Unduh source code ini dan ekstrak (jika berbentuk `.zip`).
   
2. **Pindahkan ke Folder Root Web Server**
   Pindahkan folder `SIMA` ke dalam direktori lokal server Anda:
   * Jika menggunakan XAMPP: `C:\xampp\htdocs\SIMA`
   
3. **Konfigurasi Database**
   * Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`).
   * Buat database baru dengan nama `db_sima`.
   * Lakukan **Import** file `db_sima.sql` yang berada di dalam folder proyek.

4. **Konfigurasi Koneksi (Opsional)**
   Secara *default*, sistem menggunakan kredensial standar XAMPP. Jika Anda menggunakan password root MySQL, sesuaikan di file:
   `SIMA/config/database.php`
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "password_anda"; // Kosongkan jika default
   $db   = "db_sima";
Jalankan Aplikasi
Buka browser dan akses alamat berikut:
http://localhost/SIMA

Akun Login Default
Role: ADMIN

Email: admin2@gmail.com

Password: 123

(Pastikan untuk mengganti password default ini setelah aplikasi di-deploy / di-hosting).

Struktur Direktori Utama

SIMA/
│
├── action_handlers/     # File PHP pemroses logika (Login, Register, dll)
├── assets/              # File CSS, JS, dan Gambar Statis (Logo, dll)
├── config/              # Konfigurasi koneksi database
├── functions/           # Fungsi helper (Auth checkers, Algoritma alokasi)
├── layouts/             # Komponen UI Reusable (Header, Footer, Sidebar, Navbar)
├── uploads/             # Folder penyimpanan file upload (KTP, Bukti Bayar, Laporan)
├── views/               # Antarmuka Pengguna (UI)
│   ├── admin/           # Halaman khusus Admin
│   ├── landing/         # Halaman untuk Guest / Publik
│   └── penghuni/        # Halaman khusus Penghuni Asrama
│
├── db_sima.sql          # File Dump Database MySQL
├── index.php            # Halaman Utama (Landing Page)
└── login.php            # Halaman Login Multi-Role

 Lisensi
Proyek ini dikembangkan untuk keperluan akademik / manajemen internal asrama. Dilarang memperjualbelikan ulang sistem ini tanpa izin pengembang.
