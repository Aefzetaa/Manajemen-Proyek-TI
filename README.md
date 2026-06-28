# 🏍️ Milky Garage — Sistem Manajemen Bengkel Motor

Sistem informasi manajemen bengkel motor berbasis web yang dibangun menggunakan framework **Laravel 13**. Aplikasi ini dirancang untuk mengelola seluruh operasional bengkel secara digital, mulai dari booking servis oleh pelanggan, pengelolaan service order oleh mekanik, proses pembayaran oleh kasir, hingga persetujuan dan pelaporan oleh owner.

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Cara Instalasi](#-cara-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Peran Pengguna](#-peran-pengguna)
- [Alur Proses Bisnis](#-alur-proses-bisnis)
- [Struktur Proyek](#-struktur-proyek)

## ✨ Fitur Utama

### Pelanggan
- Registrasi dan login akun
- Booking servis motor (pilih kendaraan, jenis layanan, tanggal, jam, dan keluhan)
- Melihat riwayat booking dan status servis
- Top-up saldo ZeroPay (dompet digital internal)
- Chat dengan ZoruAI (chatbot cerdas berbasis data internal)

### Mekanik
- Melihat dan menerima daftar booking masuk
- Mengelola proses servis (mulai → selesai)
- Membuat rincian Service Order (jasa, sparepart, biaya)
- Penarikan gaji yang diperoleh dari komisi servis

### Kasir
- Memproses pembayaran dari pelanggan
- Memeriksa rincian biaya service order
- Mengelola transaksi masuk

### Owner
- Dashboard analitik (statistik pendapatan, servis, performa mekanik)
- Persetujuan akhir (approval) terhadap pembayaran
- Manajemen katalog layanan dan sparepart
- Manajemen promosi
- Laporan keuangan dan operasional
- ZoruAI Analytics (analisis data cerdas berbasis data bengkel)

### Fitur Umum
- **ZoruAI** — Chatbot cerdas yang bekerja secara offline (tanpa API eksternal), memberikan rekomendasi dan informasi berdasarkan data internal bengkel
- **ZeroPay** — Sistem dompet digital internal untuk pembayaran
- **Dark Mode** — Dukungan tema gelap di seluruh halaman
- **Responsive Design** — Tampilan optimal di desktop maupun perangkat mobile
- **Manajemen Promosi** — Buat dan kelola promo aktif yang tampil di halaman utama

## 🛠️ Teknologi yang Digunakan

| Komponen       | Teknologi                |
|----------------|--------------------------|
| Framework      | Laravel 13               |
| Bahasa         | PHP 8.3+                 |
| Database       | MySQL                    |
| Frontend       | Blade Template           |
| Styling        | CSS (Vanilla)            |
| Session        | Database Driver          |
| Queue          | Database Driver          |
| Cache          | Database Driver          |
| AI/Chatbot     | Logika lokal (tanpa API) |

## 📦 Persyaratan Sistem

- **PHP** >= 8.3
- **Composer** >= 2.x
- **MySQL** >= 5.7 / MariaDB >= 10.3
- **Laragon** (disarankan) atau XAMPP/MAMP
- **Git**

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Aefzetaa/Manajemen-Proyek-TI.git
cd Manajemen-Proyek-TI
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan pengaturan database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyekti
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan:** Jika menggunakan Laragon dengan port MySQL non-standar, sesuaikan `DB_PORT` (misalnya `3307`).

### 4. Buat Database

Buat database baru dengan nama `proyekti` melalui phpMyAdmin atau terminal:

```sql
CREATE DATABASE proyekti;
```

### 5. Jalankan Migrasi

```bash
php artisan migrate
```

Atau, impor backup database yang tersedia:

```bash
mysql -u root proyekti < database/backups/proyekti_backup_2026-06-22_new_xampp_compatible.sql
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## ⚙️ Konfigurasi

### Kode Verifikasi Role (Registrasi Staf Internal)

Kode verifikasi diperlukan saat mendaftarkan akun staf melalui halaman registrasi:

| Role    | Kode Verifikasi |
|---------|-----------------|
| Owner   | `OWNER2026`     |
| Mekanik | `MECH2026`      |
| Kasir   | `CASH2026`      |

### PIN Verifikasi (Akses Fitur Tertentu)

| Role    | PIN    |
|---------|--------|
| Owner   | `0104` |
| Kasir   | `0000` |
| Mekanik | `1111` |

### Pengaturan Lainnya

| Parameter           | Nilai   | Keterangan                                 |
|---------------------|---------|--------------------------------------------|
| `SESSION_LIFETIME`  | `480`   | Masa aktif sesi = 8 jam (1 shift kerja)    |
| `DEV_QUICK_SWITCH`  | `true`  | Floating tombol ganti akun untuk testing   |

## 👥 Peran Pengguna

Sistem memiliki **4 peran** dengan hak akses berbeda:

| Peran      | Deskripsi                                                           |
|------------|---------------------------------------------------------------------|
| **Pelanggan** | Melakukan booking, melihat riwayat servis, top-up saldo, chat AI |
| **Mekanik**   | Menerima booking, mengerjakan servis, membuat service order       |
| **Kasir**     | Memproses pembayaran, mengelola transaksi                         |
| **Owner**     | Mengelola seluruh operasional, persetujuan, laporan, dan analitik |

## 🔄 Alur Proses Bisnis

```
Pelanggan membuka website
        ↓
Registrasi / Login
        ↓
Booking Servis (pilih kendaraan, layanan, jadwal, keluhan)
        ↓
Status: Scheduled
        ↓
Mekanik menerima booking → Status: Accepted
        ↓
Mekanik mulai servis → Status: In Progress
        ↓
Mekanik membuat Service Order (jasa, sparepart, biaya)
        ↓
Tagihan dibuat → Status: Pending
        ↓
Masuk antrian kasir → Status: Waiting Cashier
        ↓
Kasir memproses pembayaran → Status: Waiting Approval
        ↓
Owner menyetujui → Status: Approved
        ↓
Selesai → Status: Paid / Finished
```

## 📁 Struktur Proyek

```
ProyekTI/
├── app/
│   ├── Http/Controllers/     # Controller (Auth, Booking, Payment, dll.)
│   ├── Models/               # Model Eloquent (User, Booking, ServiceOrder, dll.)
│   └── Services/             # Logika bisnis (ZoruAI, PaymentDistribution)
├── database/
│   ├── backups/              # Backup database
│   ├── migrations/           # File migrasi database
│   └── seeders/              # Seeder data awal
├── resources/
│   └── views/                # Template Blade
│       ├── auth/             # Halaman login & registrasi
│       ├── bookings/         # Halaman booking servis
│       ├── catalog/          # Halaman katalog layanan & sparepart
│       ├── components/       # Komponen UI reusable
│       ├── dashboard.blade.php
│       ├── layouts/          # Layout utama
│       ├── payments/         # Halaman pembayaran
│       ├── reports/          # Halaman laporan & analytics
│       ├── service-orders/   # Halaman service order
│       └── welcome.blade.php # Halaman utama publik
├── routes/
│   └── web.php               # Definisi route
├── .env.example              # Template konfigurasi environment
├── composer.json             # Dependensi PHP
└── README.md                 # Dokumentasi proyek (file ini)
```

## 📄 Lisensi

Proyek ini dibuat untuk keperluan tugas mata kuliah **Manajemen Proyek Teknologi Informasi**.
