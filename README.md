# Sistem Peminjaman Barang

<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Sistem Peminjaman Barang" width="400px" />
  <br>
  <h2>Sistem Peminjaman Barang</h2>
  <p><i>Aplikasi web modern untuk mengelola peminjaman dan inventaris barang</i></p>
  
  <p>
    <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10.x" />
    <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue.js 3.x" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  </p>
</div>

Aplikasi web lengkap untuk mengelola peminjaman dan inventaris barang dengan fitur lengkap untuk admin dan pengguna. Dikembangkan dengan teknologi modern untuk memberikan pengalaman yang cepat, aman, dan responsif.

<div align="center">
  <p><strong>Dashboard Admin</strong></p>
  <img src="https://github.com/BagusCPaste/peminjaman-barang/raw/main/docs/screenshots/dashboard-preview.jpg" alt="Dashboard Preview" style="max-width: 800px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" />
  <p><i>Tampilan dashboard admin dengan statistik dan daftar barang (tambahkan screenshot Anda di docs/screenshots)</i></p>
</div>

## Fitur Utama

-   **Dashboard Informatif**: Statistik real-time tentang barang, pengguna, dan status peminjaman
-   **Manajemen Barang**: Tambah, edit, dan hapus data barang dengan berbagai kategori
-   **Sistem Peminjaman**: Proses peminjaman dan pengembalian barang yang mudah
-   **Manajemen Pengguna**: Kontrol pengguna dengan hak akses berbeda (admin dan user)
-   **Mode Gelap/Terang**: Tampilan yang menyesuaikan preferensi pengguna
-   **Responsif**: Tampilan optimal di desktop maupun perangkat mobile

## Demo

> 🔗 **Demo Aplikasi:** [soon](soon)  
> Gunakan kredensial berikut untuk mencoba:
>
> -   Admin: admin@example.com / password
> -   User: user@example.com / password

_Catatan: URL demo di atas hanya contoh. Jika Anda telah men-deploy aplikasi ini, silakan ganti dengan URL demo aktual Anda._


## Teknologi yang Digunakan

-   **Backend**: Laravel 10
-   **Frontend**: Vue.js 3 + Inertia.js
-   **UI Framework**: Tailwind CSS
-   **Database**: MySQL
-   **Autentikasi**: Laravel Breeze + Sanctum
-   **Charts**: ApexCharts
-   **Notifikasi**: Vue Toastification

## Persyaratan Sistem

-   PHP 8.1 atau lebih baru
-   Node.js 16 atau lebih baru
-   Composer
-   MySQL/MariaDB
-   Git

## Panduan Instalasi

### Langkah 1: Clone Repository

```bash
git clone https://github.com/BagusCPaste/peminjaman-barang.git
cd peminjaman-barang
```

### Langkah 2: Instal Dependensi

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Langkah 3: Setup Environment

```bash
# Salin file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman_barang
DB_USERNAME=root
DB_PASSWORD=password
```

### Langkah 4: Migrasi dan Seeding Database

```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder untuk data awal
php artisan db:seed
```

### Langkah 5: Compile Assets

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

### Langkah 6: Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Akun Default

Setelah menjalankan seeder, sistem akan membuat akun default:

### Admin

-   Email: admin@example.com
-   Password: password

### User

-   Email: user@example.com
-   Password: password

## Panduan Penggunaan

### Admin

#### Dashboard

Dashboard menampilkan statistik dan informasi penting seperti:

-   Total barang tersedia
-   Total pengguna
-   Jumlah peminjaman aktif
-   Grafik perbandingan data

#### Manajemen Barang

1. Buka menu "Data Barang"
2. Untuk menambah barang, klik tombol "Tambah Barang"
3. Isi formulir dengan informasi barang:

    - Kode Barang (harus unik)
    - Nama Barang
    - Kategori
    - Stok
    - Status

4. Untuk mengedit atau menghapus, gunakan tombol aksi pada tabel

#### Manajemen Peminjaman

1. Buka menu "Peminjaman"
2. Lihat daftar semua peminjaman
3. Klik tombol "Detail" untuk melihat informasi lengkap
4. Untuk menyetujui pengembalian, gunakan tombol "Konfirmasi Pengembalian"

#### Manajemen Pengguna

1. Buka menu "Data Pengguna"
2. Lihat daftar semua pengguna
3. Tambah pengguna baru dengan klik "Tambah Pengguna"
4. Edit atau hapus pengguna melalui tombol aksi

### User

#### Dashboard

Dashboard user menampilkan:

-   Data peminjaman aktif
-   Riwayat peminjaman
-   Status permintaan

#### Peminjaman Barang

1. Buka menu "Daftar Barang"
2. Cari barang yang ingin dipinjam
3. Klik tombol "Pinjam" pada barang yang tersedia
4. Isi formulir peminjaman:

    - Tanggal mulai
    - Tanggal kembali
    - Jumlah
    - Catatan (opsional)

5. Klik "Ajukan Peminjaman"

#### Melihat Status Peminjaman

1. Buka menu "Peminjaman Saya"
2. Lihat daftar semua peminjaman Anda dan statusnya
3. Untuk mengembalikan barang, klik "Ajukan Pengembalian"

## Mode Gelap/Terang

Aplikasi menyediakan mode gelap dan terang yang dapat diakses melalui tombol toggle di navbar. Preferensi mode akan disimpan dan akan tetap sama saat Anda kembali mengunjungi aplikasi.

## Troubleshooting

### Masalah Database

Jika mengalami masalah dengan migrasi atau koneksi database:

```bash
# Reset database dan jalankan migrasi ulang
php artisan migrate:fresh --seed
```

### Masalah Assets/JS/CSS

Jika tampilan tidak muncul dengan benar:

```bash
# Bersihkan cache
npm run dev -- --clean

# Pada beberapa kasus mungkin perlu menghapus node_modules
rm -rf node_modules
npm install
```

### Masalah Cache

```bash
# Bersihkan cache aplikasi
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Struktur Direktori Penting

-   `app/Http/Controllers` - Semua controller aplikasi
-   `resources/js/Pages` - Komponen halaman Vue.js
-   `resources/js/Components` - Komponen reusable Vue.js
-   `routes` - Definisi route aplikasi
-   `database/migrations` - Migrasi database
-   `database/seeders` - Seeder untuk data awal

## Kustomisasi Lanjutan

### Menambahkan Kategori Barang Baru

Edit file `resources/js/pages/Barang.vue` dan tambahkan opsi kategori baru di bagian form.

### Mengubah Tampilan Dashboard

Dashboard dapat dikustomisasi dengan mengubah file `resources/js/pages/Dashboard.vue`.

### Menambahkan API Endpoint Baru

1. Buat controller baru atau tambahkan method ke controller yang ada
2. Daftarkan route baru di `routes/api.php`
3. Akses melalui endpoint `/api/nama-endpoint`


