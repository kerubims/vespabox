<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Diagram - VespaBox Docs</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Load Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dokumentasi VespaBox</h1>
            <p class="text-slate-500 mt-2">Halaman khusus untuk mempratinjau diagram Mermaid sebelum disisipkan ke dokumen final.</p>
        </header>

        <!-- Main Content -->
        <div class="space-y-8">
            
            <!-- Section 1: Flowchart Booking -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-800 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-white">Flowchart: Alur Booking & POS</h2>
                    <span class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 text-xs font-medium">Flowchart</span>
                </div>
                <div class="p-6 overflow-x-auto flex justify-center bg-slate-50/50">
                    <!-- SINTAKS MERMAID -->
                    <div class="mermaid">
                        graph TD
                            %% Definisi Node
                            Guest([Pengunjung])
                            Auth{Sudah Login?}
                            Login([Halaman Login])
                            DashboardCustomer([Dashboard Pelanggan])
                            FormBooking[Isi Form Booking]
                            Antrean[Pantau Antrean Live]
                            
                            Admin([Admin Bengkel])
                            Konfirmasi[Konfirmasi Booking]
                            Proses[Proses Servis]
                            Kasir[Modul POS / Kasir]
                            Nota[Cetak Nota & Kirim WA]
                            Selesai([Servis Selesai])

                            %% Alur Pelanggan
                            Guest -->|Akses Sistem| Auth
                            Auth -- Tidak --> Login
                            Login --> DashboardCustomer
                            Auth -- Ya --> DashboardCustomer
                            
                            DashboardCustomer --> FormBooking
                            FormBooking -->|Submit| Antrean
                            
                            %% Alur Admin & Sistem
                            FormBooking -.->|Notifikasi| Admin
                            Admin --> Konfirmasi
                            Konfirmasi --> Proses
                            Proses --> Kasir
                            
                            %% Alur POS
                            Kasir -->|Input Jasa & Sparepart| Nota
                            Nota --> Selesai
                            Nota -.->|Potong Stok| DB_Sparepart[(Database Sparepart)]
                    </div>
                </div>
            </div>

            <!-- Section 2: ER Diagram -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-800 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-white">Entity Relationship Diagram (ERD)</h2>
                    <span class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 text-xs font-medium">ERD</span>
                </div>
                <div class="p-6 overflow-x-auto flex justify-center bg-slate-50/50">
                    <!-- SINTAKS MERMAID -->
                    <div class="mermaid">
                        erDiagram
                            USERS ||--o{ BOOKINGS : "makes"
                            USERS {
                                int id PK
                                string nama
                                string email
                                string no_wa
                                string role "admin/customer"
                            }
                            
                            BOOKINGS ||--|| TRANSACTIONS : "generates"
                            BOOKINGS {
                                int id PK
                                int user_id FK
                                string no_booking
                                date tgl_booking
                                string plat_nomor
                                string status
                            }
                            
                            TRANSACTIONS ||--|{ TRANSACTION_DETAILS : "has"
                            TRANSACTIONS {
                                int id PK
                                int booking_id FK
                                decimal grand_total
                                date tgl_transaksi
                            }

                            TRANSACTION_DETAILS }|--|| SPAREPARTS : "includes item"
                            TRANSACTION_DETAILS {
                                int id PK
                                int transaction_id FK
                                int sparepart_id FK
                                int qty
                                decimal subtotal
                            }
                            
                            SPAREPARTS {
                                int id PK
                                string kode_barang
                                string nama
                                int stok
                            }
                    </div>
                </div>
            </div>

            <!-- Section 3: Use Case Diagram -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-800 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-white">Use Case Diagram</h2>
                    <span class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 text-xs font-medium">Use Case</span>
                </div>
                <div class="p-6 overflow-x-auto flex justify-center bg-slate-50/50">
                    <!-- SINTAKS MERMAID -->
                    <div class="mermaid">
                        flowchart LR
                            %% Mendefinisikan Aktor
                            Customer["👤 Pelanggan"]
                            Admin["🧑‍💻 Admin / Kasir"]

                            %% Batas Sistem (System Boundary)
                            subgraph VespaBox ["Sistem VespaBox"]
                                direction TB
                                
                                %% Use Cases (Notasi Elips/Pill)
                                UC1([Login / Register])
                                UC2([Melihat Katalog Sparepart])
                                UC3([Melakukan Booking Service])
                                UC4([Memantau Antrean Live])
                                UC5([Melihat Riwayat Servis])
                                
                                UC6([Mengelola Inventori Sparepart])
                                UC7([Konfirmasi & Kelola Booking])
                                UC8([Memproses Transaksi POS])
                                UC9([Cetak Nota & Kirim WA])
                                UC10([Melihat Laporan Pendapatan])
                            end

                            %% Relasi Aktor ke Use Case
                            Customer --- UC1
                            Customer --- UC2
                            Customer --- UC3
                            Customer --- UC4
                            Customer --- UC5
                            
                            Admin --- UC1
                            Admin --- UC6
                            Admin --- UC7
                            Admin --- UC8
                            Admin --- UC10
                            
                            %% Relasi antar Use Case (Include/Extend)
                            UC8 -.->|<< include >>| UC9
                            UC3 -.->|<< include >>| UC1
                            UC4 -.->|<< include >>| UC3
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm border border-blue-100">
            <strong>Tips:</strong> Anda dapat mengedit diagram ini secara langsung dengan mengubah blok <code>&lt;div class="mermaid"&gt;</code> di dalam file <code>resources/views/docs/preview.blade.php</code>.
        </div>
    </div>
</body>
</html>
