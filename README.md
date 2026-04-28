# VespaBox — Sistem Booking & POS Bengkel Vespa

VespaBox adalah aplikasi berbasis web untuk manajemen bengkel Vespa yang mencakup fitur **Live Booking Antrean**, **Manajemen Sparepart**, **Point of Sales (POS)**, dan **Laporan Transaksi**. Aplikasi ini dibangun menggunakan Laravel 11, Tailwind CSS, Alpine.js, dan Laravel Reverb untuk fitur realtime.

## Persyaratan Sistem

Pastikan sistem Anda sudah menginstal:
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL atau MariaDB
- Git (opsional)

## Instalasi & Konfigurasi

Berikut adalah langkah-langkah untuk menyiapkan dan menjalankan proyek ini di lingkungan lokal Anda:

### 1. Clone & Install Dependencies
Clone repository (jika menggunakan git) dan install seluruh dependensi PHP dan Node.js:
```bash
composer install
npm install
```

### 2. Setup Environment Variables
Buat salinan file `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database Anda di dalam file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vespabox
DB_USERNAME=root
DB_PASSWORD=
```
Jangan lupa untuk membuat database kosong di MySQL/MariaDB dengan nama `vespabox` (atau sesuai nama yang Anda atur).

### 3. Generate App Key & Database Setup
Jalankan perintah ini untuk membuat *application key* dan memigrasikan tabel beserta data percobaan (seeder):
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```
*Catatan: Flag `--seed` sangat penting agar tabel langsung terisi dengan data akun, slot jadwal, sparepart, dan contoh transaksi.*

### 4. Build Aset Frontend
Jalankan Vite untuk mengkompilasi file Tailwind CSS dan JavaScript:
```bash
npm run build
```

---

## Memperbarui Aplikasi (Jika Sudah Pernah Clone)

Jika Anda sebelumnya sudah pernah melakukan instalasi proyek ini di laptop lain atau sekadar ingin menarik pembaruan fitur terbaru dari repositori, jalankan perintah-perintah berikut secara berurutan di terminal:

```bash
# 1. Tarik pembaruan kode terbaru
git pull origin main

# 2. Perbarui dependensi backend
composer install

# 3. Perbarui dependensi frontend dan build aset
npm install
npm run build

# 4. Jalankan migrasi terbaru (PENTING: untuk menambahkan kolom database baru yang diperlukan fitur terbaru)
php artisan migrate

# 5. Bersihkan cache sistem
php artisan optimize:clear
```

---

## Menjalankan Aplikasi (Local Development)

Proyek ini menggunakan **Laravel Reverb** (Websocket) dan **Queues** untuk mendukung fitur *Live Antrean* dan notifikasi *Realtime* di halaman admin. Agar aplikasi dapat berjalan dengan sempurna, Anda memerlukan **4 proses yang berjalan bersamaan**. 

Anda memiliki dua pilihan untuk menjalankannya:

### Opsi A: Cara Cepat (Disarankan)
Laravel 11 menyediakan fitur untuk menjalankan semua proses (Server, Queue, Reverb, dan Vite) sekaligus dalam satu perintah. Buka terminal dan jalankan:

```bash
composer run dev
```

### Opsi B: Cara Manual (Menggunakan 4 Tab Terminal)
Jika Anda ingin melihat log dari masing-masing proses, buka 4 tab terminal berbeda pada folder proyek dan jalankan perintah berikut:

1. **Terminal 1 (Web Server):**
   ```bash
   php artisan serve
   ```
2. **Terminal 2 (Frontend HMR):**
   ```bash
   npm run dev
   ```
3. **Terminal 3 (Websocket Server):**
   ```bash
   php artisan reverb:start
   ```
4. **Terminal 4 (Queue Worker):**
   ```bash
   php artisan queue:listen
   ```

Aplikasi sekarang dapat diakses melalui browser di alamat: **`http://localhost:8000`**

---

## Akses Pengguna Uji Coba

Gunakan kredensial berikut untuk masuk dan menguji fitur sistem:

**1. Akses Admin (Panel Manajemen, POS, Konfirmasi Booking)**
- Email: `admin@vespabox.com`
- Password: `password`

**2. Akses Customer (Booking Servis, Antrean, Ulasan)**
- Email: `budi@example.com` atau `rina@example.com`
- Password: `password`

---

## Fitur Utama & Cara Pengujian Realtime

Untuk menguji fitur WebSocket (Laravel Reverb) berjalan dengan baik:
1. Buka browser **Tab A** dan login sebagai **Admin** (`admin@vespabox.com`). Masuk ke Dashboard atau halaman Booking.
2. Buka browser **Tab B** (Incognito/Private) dan login sebagai **Customer** (`budi@example.com`).
3. Pada tab Customer, lakukan **Book Appointment**.
4. Perhatikan pada tab Admin, ikon lonceng notifikasi (di sudut kanan atas) akan bertambah secara *realtime* dengan alert pemberitahuan.
5. Selanjutnya, minta Customer masuk ke halaman **Live Queue (Antrean)**.
6. Pada tab Admin, ubah status antrean customer tersebut dari "Menunggu" menjadi "Sedang Dikerjakan".
7. Pada tab Customer, halaman Live Queue akan otomatis melakukan refresh dan menampilkan status terbaru tanpa perlu me-reload halaman secara manual.
