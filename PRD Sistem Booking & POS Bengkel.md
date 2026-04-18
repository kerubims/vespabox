# **Product Requirements Document (PRD)**

**Nama Produk:** Sistem Terintegrasi Booking Service & POS Bengkel

**Platform:** Web Application (Responsive)

**Target Pengguna:** Pelanggan Bengkel dan Admin/Kasir Bengkel

**Tech Stack Saran:** Laravel (PHP), MySQL, Bootstrap/Tailwind CSS, DomPDF (untuk cetak nota).

## **1\. Pendahuluan**

### **1.1 Latar Belakang**

Bengkel seringkali mengalami kendala dalam mengatur antrean pelanggan dan mencatat penggunaan *sparepart* secara manual. Pencatatan manual menggunakan nota kertas rawan hilang, rentan kesalahan hitung, dan menyulitkan pelacakan stok. Di sisi lain, pelanggan membutuhkan transparansi terkait antrean dan riwayat perawatan kendaraan mereka.

### **1.2 Tujuan Produk**

Membuat sistem berbasis web yang memungkinkan pelanggan melakukan *booking service* secara mandiri dan memantau antrean, sekaligus menyediakan modul Point of Sale (POS) bagi Admin untuk mencatat transaksi, memotong stok *sparepart* secara otomatis, mencetak nota, dan mengelola laporan operasional.

## **2\. Peran Pengguna (User Personas)**

Sistem ini memiliki 2 aktor utama:

1. **Pelanggan (Customer):** Orang yang memiliki akun pada sistem untuk melakukan *booking* jadwal, melihat daftar antrean, dan memantau riwayat servis kendaraannya. (Guest hanya bisa melihat halaman utama dan katalog sparepart).  
2. **Admin / Kasir:** Staf bengkel yang mengelola data *sparepart*, menyetujui *booking*, memproses transaksi di kasir, melihat laporan, dan mencetak nota.

## **3\. Ruang Lingkup (Scope)**

**In-Scope (Masuk dalam cakupan):**

* Landing Page dan Katalog Sparepart (Publik/Tanpa Login).  
* Autentikasi (Registrasi dan Login) untuk Pelanggan.  
* Dashboard Pelanggan (Form Booking, Riwayat Servis, Pantau Antrean).  
* Dashboard manajemen Admin.  
* CRUD dan Manajemen Stok *Sparepart* dengan Notifikasi Stok Menipis.  
* Modul Transaksi / POS (penggabungan biaya jasa dan *sparepart*).  
* Pembuatan Invoice PDF dan pengiriman via URL WhatsApp.  
* Laporan Pendapatan (Jasa & Sparepart).  
* Riwayat Servis Kendaraan.

**Out-of-Scope (Tidak masuk dalam cakupan):**

* Integrasi *Payment Gateway* otomatis (Midtrans, Xendit, dll). Pembayaran tetap offline/transfer manual.

## **4\. Fitur Utama & Kebutuhan Fungsional (Functional Requirements)**

### **4.1. Modul Publik (Tanpa Login)**

* **Landing Page:** Menampilkan informasi bengkel, jam operasional, dan layanan.  
* **Katalog Sparepart:** Menampilkan daftar *sparepart* beserta harga estimasinya agar calon pelanggan bisa melihat ketersediaan barang.

### **4.2. Modul Pelanggan (Membutuhkan Login)**

* **Autentikasi:** Registrasi akun baru, Login, dan Logout.  
* **Form Booking Service:**  
  * Form input: Nama, No WhatsApp, Plat Nomor, Merek/Tipe Kendaraan, Tanggal & Jam Booking, Keluhan/Jenis Servis.  
  * Setelah *submit*, sistem menghasilkan **Kode Booking**.  
* **Daftar Antrean (Live Tracking):**  
  * Setelah berhasil booking, pelanggan diarahkan ke halaman "Daftar Antrean Hari Ini".  
  * Pelanggan dapat melihat urutan kendaraannya dan status saat ini (Menunggu, Dikonfirmasi, Sedang Dikerjakan, Selesai).  
* **Riwayat Servis Kendaraan (Personal):**  
  * Pelanggan dapat melihat riwayat perbaikan motornya sendiri di masa lalu, termasuk detail *sparepart* yang pernah diganti.

### **4.3. Modul Admin \- Manajemen Sparepart & Stok (Inventory)**

* **CRUD Master Barang:** Menambah, mengedit, melihat, dan menghapus data *sparepart* (Kode, Nama, Kategori, Harga Beli, Harga Jual, Stok).  
* **Manajemen Stok & Peringatan Stok Menipis (Low Stock Alert):**  
  * Fitur penambahan stok (Restock).  
  * Pengurangan otomatis saat transaksi POS selesai.  
  * **Indikator Peringatan:** Tabel atau *badge* khusus di dashboard Admin yang menampilkan daftar barang dengan stok di bawah batas minimum (misal: \< 5 pcs).

### **4.4. Modul Admin \- Manajemen Booking & Riwayat**

* **Daftar Antrean & Konfirmasi:** \* Admin melihat daftar *booking* masuk dan mengubah statusnya.  
* **Riwayat Servis (Vehicle History Record) Global:**  
  * Admin dapat mencari histori perbaikan berdasarkan **Plat Nomor** pelanggan. Menampilkan riwayat keluhan, tanggal servis, dan *sparepart* yang digunakan sebelumnya.

### **4.5. Modul Admin \- Point of Sale (Kasir) & Laporan**

* **Halaman Transaksi POS:**  
  * Pilih "Kode Booking" yang berstatus *Selesai Servis* (menarik data pelanggan otomatis).  
  * Input Biaya Jasa Servis dan penambahan *Sparepart*.  
  * Kalkulasi total biaya otomatis.  
* **Penyelesaian Transaksi (Checkout):**  
  * Memotong stok *sparepart* secara permanen dan mengubah status *booking* menjadi "Lunas".  
* **Cetak Nota PDF & Integrasi WhatsApp:**  
  * Generate nota digital PDF.  
  * **Tombol "Kirim Nota ke WA":** Menggunakan format URL dinamis (wa.me) untuk membuka WhatsApp Web dengan pesan berisi rincian tagihan dan link nota PDF ke nomor WhatsApp pelanggan.  
* **Laporan Pendapatan Sederhana:**  
  * Rekapitulasi harian dan bulanan (Tabel/Grafik).  
  * Pemisahan sumber pendapatan: Total Pendapatan Jasa vs. Total Pendapatan Sparepart.  
  * Fitur *Export* data ke Excel/PDF.

## **5\. Alur Sistem (System Flow)**

1. **Akses Awal:** Pengguna (Guest) mengunjungi web, dapat melihat Landing Page dan Katalog Sparepart.  
2. **Booking & Antrean:** \* Pengguna *Login* atau *Register*.  
   * Mengisi form booking service.  
   * Sistem menampilkan Kode Booking dan mengarahkan pengguna ke halaman **Daftar Antrean** untuk memantau urutan servis secara *real-time*.  
3. **Kedatangan & Pengerjaan:** Pelanggan datang \-\> Admin mengubah status booking menjadi "Proses Servis" \-\> Mekanik mengerjakan kendaraan.  
4. **Kasir (POS):** Servis selesai \-\> Admin membuka menu POS \-\> Memilih kode booking \-\> Memasukkan Jasa Servis dan *Sparepart* yang terpakai \-\> Sistem mengkalkulasi total biaya.  
5. **Pembayaran & Nota:** Pelanggan membayar di tempat (tunai/transfer bank luar sistem) \-\> Admin klik "Selesaikan Transaksi" (Stok otomatis berkurang) \-\> Admin mencetak nota PDF atau menekan tombol **"Kirim ke WA"** untuk mengirimkan tagihan langsung ke WhatsApp pelanggan.

## **6\. Database Diagram Sederhana (Konsep)**

* users (id, nama, email, password, no\_wa, role: admin/customer)  
* vehicles (id, user\_id, plat\_nomor, merek\_tipe) \-\> Opsional untuk menyimpan garasi user  
* bookings (id, user\_id, no\_booking, tgl\_booking, jam\_booking, plat\_nomor, keluhan, status)  
* products (id, kode\_barang, nama, harga\_beli, harga\_jual, stok, batas\_stok\_minimum)  
* services (id, nama\_jasa, harga\_standar)  
* transactions (id, booking\_id, total\_jasa, total\_sparepart, grand\_total, tgl\_transaksi)  
* transaction\_details (id, transaction\_id, product\_id, qty, harga\_satuan, subtotal)