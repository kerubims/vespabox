<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sparepart;
use App\Models\Slot;
use App\Models\Booking;
use App\Models\PosTransaction;
use App\Models\PosItem;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // 1. USERS
        // ============================================
        $admin = User::create([
            'name' => 'Admin VespaBox',
            'email' => 'admin@vespabox.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $customers = [];
        $customerData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '089876543210'],
            ['name' => 'Rina Wati', 'email' => 'rina@example.com', 'phone' => '081122334455'],
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'phone' => '085566778899'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '087744556677'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@example.com', 'phone' => '082233445566'],
        ];

        foreach ($customerData as $data) {
            $customers[] = User::create(array_merge($data, [
                'role' => 'customer',
                'password' => Hash::make('password'),
            ]));
        }

        // ============================================
        // 2. SPAREPARTS
        // ============================================
        $spareparts = [
            ['kode' => 'SP-001', 'nama' => 'Kampas Rem Depan Vespa Sprint', 'kategori' => 'Pengereman', 'harga_beli' => 120000, 'harga_jual' => 150000, 'stok' => 15, 'stok_minimum' => 5],
            ['kode' => 'SP-002', 'nama' => 'Oli Mesin 10W-40 Motul', 'kategori' => 'Oli & Cairan', 'harga_beli' => 90000, 'harga_jual' => 125000, 'stok' => 50, 'stok_minimum' => 10],
            ['kode' => 'SP-003', 'nama' => 'V-Belt Original Vespa Matic', 'kategori' => 'CVT', 'harga_beli' => 200000, 'harga_jual' => 250000, 'stok' => 3, 'stok_minimum' => 5],
            ['kode' => 'SP-004', 'nama' => 'Filter Udara Original', 'kategori' => 'Filter', 'harga_beli' => 50000, 'harga_jual' => 75000, 'stok' => 4, 'stok_minimum' => 5],
            ['kode' => 'SP-005', 'nama' => 'Busi NGK CR7HSA', 'kategori' => 'Pengapian', 'harga_beli' => 20000, 'harga_jual' => 35000, 'stok' => 2, 'stok_minimum' => 5],
            ['kode' => 'SP-006', 'nama' => 'Kampas Rem Belakang', 'kategori' => 'Pengereman', 'harga_beli' => 110000, 'harga_jual' => 140000, 'stok' => 3, 'stok_minimum' => 5],
            ['kode' => 'SP-007', 'nama' => 'Roller Set CVT 12g', 'kategori' => 'CVT', 'harga_beli' => 85000, 'harga_jual' => 120000, 'stok' => 8, 'stok_minimum' => 5],
            ['kode' => 'SP-008', 'nama' => 'Lampu Depan LED H4', 'kategori' => 'Kelistrikan', 'harga_beli' => 150000, 'harga_jual' => 200000, 'stok' => 10, 'stok_minimum' => 3],
            ['kode' => 'SP-009', 'nama' => 'Air Radiator Coolant 1L', 'kategori' => 'Oli & Cairan', 'harga_beli' => 35000, 'harga_jual' => 55000, 'stok' => 20, 'stok_minimum' => 5],
            ['kode' => 'SP-010', 'nama' => 'Per CVT 1500RPM', 'kategori' => 'CVT', 'harga_beli' => 45000, 'harga_jual' => 75000, 'stok' => 12, 'stok_minimum' => 5],
            ['kode' => 'SP-011', 'nama' => 'Kabel Gas Vespa Sprint', 'kategori' => 'Aksesoris', 'harga_beli' => 30000, 'harga_jual' => 50000, 'stok' => 6, 'stok_minimum' => 3],
            ['kode' => 'SP-012', 'nama' => 'Seal Shock Depan', 'kategori' => 'Suspensi', 'harga_beli' => 75000, 'harga_jual' => 110000, 'stok' => 1, 'stok_minimum' => 3],
        ];

        $sparepartModels = [];
        foreach ($spareparts as $item) {
            $sparepartModels[] = Sparepart::create($item);
        }

        // ============================================
        // 3. SLOTS
        // ============================================
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $times = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

        foreach ($days as $day) {
            foreach ($times as $time) {
                $isActive = true;
                $kapasitas = 2;
                
                if ($day === 'Jumat' && ($time === '11:00' || $time === '13:00')) {
                    $isActive = false;
                    $kapasitas = 0;
                }

                Slot::create([
                    'hari' => $day,
                    'jam' => $time,
                    'kapasitas' => $kapasitas,
                    'is_active' => $isActive,
                ]);
            }
        }

        // ============================================
        // 4. BOOKINGS
        // ============================================
        $today = Carbon::today();

        // --- Bookings Hari Ini (beragam status) ---
        $b1 = Booking::create([
            'kode_booking' => 'VB-A1B2C3',
            'user_id' => $customers[0]->id,
            'plat_nomor' => 'B 1234 XY',
            'kendaraan' => 'Vespa Sprint 150',
            'keluhan' => 'Servis rutin bulanan, ganti oli mesin',
            'tanggal' => $today,
            'jam' => '09:00',
            'status' => 'Sedang Dikerjakan',
        ]);

        $b2 = Booking::create([
            'kode_booking' => 'VB-D4E5F6',
            'user_id' => $customers[1]->id,
            'plat_nomor' => 'D 4567 ZA',
            'kendaraan' => 'Vespa Primavera 150',
            'keluhan' => 'CVT bunyi kasar, cek roller',
            'tanggal' => $today,
            'jam' => '10:00',
            'status' => 'Dikonfirmasi',
        ]);

        $b3 = Booking::create([
            'kode_booking' => 'VB-G7H8I9',
            'user_id' => $customers[2]->id,
            'plat_nomor' => 'F 9876 KL',
            'kendaraan' => 'Vespa GTS 300',
            'keluhan' => 'Rem depan kurang pakem, minta ganti kampas rem',
            'tanggal' => $today,
            'jam' => '11:00',
            'status' => 'Menunggu',
        ]);

        $b4 = Booking::create([
            'kode_booking' => 'VB-J1K2L3',
            'user_id' => $customers[3]->id,
            'plat_nomor' => 'B 5555 ZZ',
            'kendaraan' => 'Vespa LX 125',
            'keluhan' => 'Motor brebet di RPM rendah',
            'tanggal' => $today,
            'jam' => '13:00',
            'status' => 'Menunggu',
        ]);

        $b5 = Booking::create([
            'kode_booking' => 'VB-M4N5O6',
            'user_id' => $customers[4]->id,
            'plat_nomor' => 'T 1111 AB',
            'kendaraan' => 'Vespa S 125',
            'keluhan' => 'Lampu depan mati, ganti bohlam',
            'tanggal' => $today,
            'jam' => '14:00',
            'status' => 'Menunggu',
        ]);

        // --- Bookings Selesai (dengan transaksi POS) ---
        $b6 = Booking::create([
            'kode_booking' => 'VB-P7Q8R9',
            'user_id' => $customers[0]->id,
            'plat_nomor' => 'B 1234 XY',
            'kendaraan' => 'Vespa Sprint 150',
            'keluhan' => 'Servis rutin bulanan, ganti oli mesin, cek CVT',
            'tanggal' => $today->copy()->subDays(2),
            'jam' => '10:00',
            'status' => 'Selesai',
            'is_reviewed' => true,
        ]);

        $b7 = Booking::create([
            'kode_booking' => 'VB-S1T2U3',
            'user_id' => $customers[1]->id,
            'plat_nomor' => 'D 4567 ZA',
            'kendaraan' => 'Vespa Primavera 150',
            'keluhan' => 'Ganti kampas rem depan belakang',
            'tanggal' => $today->copy()->subDays(3),
            'jam' => '09:00',
            'status' => 'Selesai',
            'is_reviewed' => true,
        ]);

        $b8 = Booking::create([
            'kode_booking' => 'VB-V4W5X6',
            'user_id' => $customers[2]->id,
            'plat_nomor' => 'F 9876 KL',
            'kendaraan' => 'Vespa GTS 300',
            'keluhan' => 'Servis besar, turun mesin, ganti piston set',
            'tanggal' => $today->copy()->subDays(5),
            'jam' => '08:00',
            'status' => 'Selesai',
            'is_reviewed' => true,
        ]);

        $b9 = Booking::create([
            'kode_booking' => 'VB-Y7Z8A1',
            'user_id' => $customers[3]->id,
            'plat_nomor' => 'B 5555 ZZ',
            'kendaraan' => 'Vespa LX 125',
            'keluhan' => 'CVT berisik, ganti v-belt dan roller',
            'tanggal' => $today->copy()->subDays(7),
            'jam' => '14:00',
            'status' => 'Selesai',
            'is_reviewed' => false,
        ]);

        $b10 = Booking::create([
            'kode_booking' => 'VB-B2C3D4',
            'user_id' => $customers[4]->id,
            'plat_nomor' => 'T 1111 AB',
            'kendaraan' => 'Vespa S 125',
            'keluhan' => 'Ganti oli dan filter udara',
            'tanggal' => $today->copy()->subDays(10),
            'jam' => '11:00',
            'status' => 'Selesai',
            'is_reviewed' => false,
        ]);

        // --- Booking Dibatalkan ---
        Booking::create([
            'kode_booking' => 'VB-E5F6G7',
            'user_id' => $customers[0]->id,
            'plat_nomor' => 'B 1234 XY',
            'kendaraan' => 'Vespa Sprint 150',
            'keluhan' => 'Ganti ban depan',
            'tanggal' => $today->copy()->subDays(4),
            'jam' => '15:00',
            'status' => 'Dibatalkan',
        ]);

        // --- Bookings Lama (minggu lalu) ---
        $b12 = Booking::create([
            'kode_booking' => 'VB-H8I9J1',
            'user_id' => $customers[0]->id,
            'plat_nomor' => 'B 1234 XY',
            'kendaraan' => 'Vespa Sprint 150',
            'keluhan' => 'Ganti busi dan cek kelistrikan',
            'tanggal' => $today->copy()->subDays(14),
            'jam' => '09:00',
            'status' => 'Selesai',
            'is_reviewed' => true,
        ]);

        // ============================================
        // 5. POS TRANSACTIONS & ITEMS
        // ============================================

        // Transaction 1: Booking b6 (Servis rutin + oli)
        $trx1 = PosTransaction::create([
            'booking_id' => $b6->id,
            'user_id' => $admin->id,
            'subtotal' => 275000,
            'tax_amount' => 30250,
            'total' => 305250,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b6->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx1->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Servis Ringan (Tune Up)',
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx1->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[1]->id, // Oli
            'item_name' => 'Oli Mesin 10W-40 Motul',
            'qty' => 1,
            'price' => 125000,
            'subtotal' => 125000,
        ]);

        // Transaction 2: Booking b7 (Ganti kampas rem)
        $trx2 = PosTransaction::create([
            'booking_id' => $b7->id,
            'user_id' => $admin->id,
            'subtotal' => 440000,
            'tax_amount' => 48400,
            'total' => 488400,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b7->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx2->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Jasa Ganti Kampas Rem',
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx2->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[0]->id, // Kampas rem depan
            'item_name' => 'Kampas Rem Depan Vespa Sprint',
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx2->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[5]->id, // Kampas rem belakang
            'item_name' => 'Kampas Rem Belakang',
            'qty' => 1,
            'price' => 140000,
            'subtotal' => 140000,
        ]);

        // Transaction 3: Booking b8 (Servis besar)
        $trx3 = PosTransaction::create([
            'booking_id' => $b8->id,
            'user_id' => $admin->id,
            'subtotal' => 900000,
            'tax_amount' => 99000,
            'total' => 999000,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b8->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx3->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Servis Besar / Turun Mesin',
            'qty' => 1,
            'price' => 650000,
            'subtotal' => 650000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx3->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[2]->id, // V-Belt
            'item_name' => 'V-Belt Original Vespa Matic',
            'qty' => 1,
            'price' => 250000,
            'subtotal' => 250000,
        ]);

        // Transaction 4: Booking b9 (CVT)
        $trx4 = PosTransaction::create([
            'booking_id' => $b9->id,
            'user_id' => $admin->id,
            'subtotal' => 455000,
            'tax_amount' => 50050,
            'total' => 505050,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b9->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx4->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Servis CVT',
            'qty' => 1,
            'price' => 85000,
            'subtotal' => 85000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx4->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[2]->id, // V-Belt
            'item_name' => 'V-Belt Original Vespa Matic',
            'qty' => 1,
            'price' => 250000,
            'subtotal' => 250000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx4->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[6]->id, // Roller Set
            'item_name' => 'Roller Set CVT 12g',
            'qty' => 1,
            'price' => 120000,
            'subtotal' => 120000,
        ]);

        // Transaction 5: Booking b10 (Ganti oli + filter)
        $trx5 = PosTransaction::create([
            'booking_id' => $b10->id,
            'user_id' => $admin->id,
            'subtotal' => 350000,
            'tax_amount' => 38500,
            'total' => 388500,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b10->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx5->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Servis Ringan (Tune Up)',
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx5->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[1]->id, // Oli
            'item_name' => 'Oli Mesin 10W-40 Motul',
            'qty' => 1,
            'price' => 125000,
            'subtotal' => 125000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx5->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[3]->id, // Filter udara
            'item_name' => 'Filter Udara Original',
            'qty' => 1,
            'price' => 75000,
            'subtotal' => 75000,
        ]);

        // Transaction 6: Booking b12 (Ganti busi)
        $trx6 = PosTransaction::create([
            'booking_id' => $b12->id,
            'user_id' => $admin->id,
            'subtotal' => 185000,
            'tax_amount' => 20350,
            'total' => 205350,
            'status_pembayaran' => 'Lunas',
            'created_at' => $b12->tanggal,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx6->id,
            'item_type' => 'jasa',
            'item_id' => null,
            'item_name' => 'Servis Ringan (Tune Up)',
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);

        PosItem::create([
            'pos_transaction_id' => $trx6->id,
            'item_type' => 'sparepart',
            'item_id' => $sparepartModels[4]->id, // Busi
            'item_name' => 'Busi NGK CR7HSA',
            'qty' => 1,
            'price' => 35000,
            'subtotal' => 35000,
        ]);

        // ============================================
        // 6. REVIEWS
        // ============================================
        Review::create([
            'booking_id' => $b6->id,
            'user_id' => $customers[0]->id,
            'rating' => 5,
            'comment' => 'Pelayanan sangat cepat, ruang tunggu nyaman, dan mekaniknya sangat komunikatif menjelaskan masalah pada CVT motor saya. Terima kasih VespaBox!',
            'admin_reply' => 'Terima kasih banyak Kak Budi atas kepercayaannya! Semoga Vespa Sprint-nya makin nyaman dikendarai. Ditunggu kedatangannya lagi!',
        ]);

        Review::create([
            'booking_id' => $b7->id,
            'user_id' => $customers[1]->id,
            'rating' => 4,
            'comment' => 'Secara keseluruhan bagus, cuma kemarin waktu antrean agak molor sekitar 15 menit dari jadwal booking.',
            'admin_reply' => 'Halo Kak Rina, mohon maaf atas keterlambatannya. Kami akan terus mengevaluasi estimasi waktu pengerjaan kami agar lebih akurat ke depannya. Terima kasih atas masukannya!',
        ]);

        Review::create([
            'booking_id' => $b8->id,
            'user_id' => $customers[2]->id,
            'rating' => 5,
            'comment' => 'Servis besar GTS saya hasilnya sangat memuaskan. Mesin jadi halus banget, tarikan responsif. Mekaniknya berpengalaman!',
            'admin_reply' => null,
        ]);

        Review::create([
            'booking_id' => $b12->id,
            'user_id' => $customers[0]->id,
            'rating' => 5,
            'comment' => 'Sudah kedua kalinya servis disini, selalu puas. Recommended banget buat sesama Vesparian!',
            'admin_reply' => 'Wah senang sekali Kak Budi puas dengan pelayanan kami! Terima kasih sudah menjadi pelanggan setia VespaBox.',
        ]);
    }
}
