# Panduan Instalasi Milky Garage

Panduan ini berisi langkah-langkah lengkap untuk menginstal dan menjalankan proyek Milky Garage menggunakan **Laragon** atau **XAMPP**.

## Persyaratan Sistem (Prerequisites)
Pastikan Anda telah menginstal perangkat lunak berikut sebelum memulai:
1. **PHP** versi 8.3 atau yang lebih baru.
2. **Composer** versi 2.x.
3. **Git** untuk melakukan clone repository.
4. **Laragon** (Sangat disarankan) atau **XAMPP**.

---

## Langkah Instalasi

### 1. Clone Repository
Buka terminal (Command Prompt, PowerShell, atau Git Bash) dan arahkan ke folder web server Anda.
- **Laragon**: `C:\laragon\www\`
- **XAMPP**: `C:\xampp\htdocs\`

Jalankan perintah berikut:
```bash
git clone https://github.com/Aefzetaa/Manajemen-Proyek-TI.git
cd Manajemen-Proyek-TI
```

### 2. Install Dependensi (Composer)
Instal semua dependensi PHP yang dibutuhkan proyek (termasuk Laravel) dengan perintah:
```bash
composer install
```

### 3. Konfigurasi File Environment (`.env`)
Aplikasi Laravel membutuhkan file konfigurasi `.env`. 
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Atau Anda bisa menyalin dan mengubah namanya secara manual melalui File Explorer).*

Setelah itu, buat *application key* baru:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database (Laragon & XAMPP)

Buka file `.env` yang baru saja Anda buat menggunakan teks editor (VS Code, Notepad, dll), lalu cari bagian konfigurasi database dan sesuaikan dengan environment Anda.

#### 🔹 Pengguna Laragon
Biasanya Laragon menggunakan MySQL dengan port standar `3306`. Jika Anda mengubah port MySQL Anda (misal ke `3307`), pastikan untuk menggantinya.
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306  # Ubah ke 3307 jika MySQL Laragon Anda menggunakan port tersebut
DB_DATABASE=proyekti
DB_USERNAME=root
DB_PASSWORD=  # Biasanya kosong di Laragon
```

#### 🔹 Pengguna XAMPP
XAMPP menggunakan port standar `3306`.
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyekti
DB_USERNAME=root
DB_PASSWORD=  # Biasanya kosong di XAMPP
```

### 5. Buat Database
Pastikan **MySQL** pada panel Laragon atau XAMPP Anda sudah berjalan (`Start`).
1. Buka aplikasi manajemen database Anda (seperti HeidiSQL bawaan Laragon, atau phpMyAdmin di `http://localhost/phpmyadmin`).
2. Buat database baru dengan nama **`proyekti`** (harus sama dengan `DB_DATABASE` di `.env`).

### 6. Migrasi atau Import Database
Ada dua opsi untuk menyiapkan struktur tabel dan data.

**Opsi A: Menggunakan File Backup (Disarankan jika ingin menggunakan data testing bawaan)**
Proyek ini sudah dilengkapi dengan file backup database (termasuk data dummy untuk testing). Anda dapat mengimpornya:
- **Melalui phpMyAdmin/HeidiSQL**: Import file `database/backups/proyekti_backup_2026-06-22_new_xampp_compatible.sql` ke dalam database `proyekti`.
- **Melalui Terminal**:
  ```bash
  mysql -u root proyekti < database/backups/proyekti_backup_2026-06-22_new_xampp_compatible.sql
  ```
  *(Jika port Anda bukan 3306, sesuaikan perintah: `mysql -u root -P 3307 proyekti < ...`)*

**Opsi B: Jalankan Migrasi Bersih (Tanpa data)**
Jika Anda ingin memulai database dari nol:
```bash
php artisan migrate
```

### 7. Jalankan Aplikasi
Langkah terakhir adalah menjalankan server lokal Laravel. Buka terminal di dalam folder proyek Anda dan ketikkan:
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser Anda di alamat:
**`http://localhost:8000`**

*(Catatan: Untuk pengguna Laragon, jika Anda tidak menggunakan `php artisan serve`, Anda juga bisa mengaksesnya langsung melalui URL otomatis Laragon, misalnya `http://manajemen-proyek-ti.test`)*
