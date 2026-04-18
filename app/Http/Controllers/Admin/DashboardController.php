<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Sparepart;
use App\Models\PosTransaction;
use App\Models\PosItem;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // KPI: Pendapatan Hari Ini
        $pendapatanHariIni = PosTransaction::whereDate('created_at', $today)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total');

        // KPI: Booking Hari Ini
        $bookingHariIni = Booking::whereDate('tanggal', $today)->count();

        // KPI: Stok Menipis
        $stokMenipis = Sparepart::whereColumn('stok', '<', 'stok_minimum')->count();

        // KPI: Rating Rata-rata
        $ratingRataRata = Review::avg('rating') ?? 0;

        // Booking Hari Ini untuk tabel
        $bookingsToday = Booking::with('user')
            ->whereDate('tanggal', $today)
            ->orderBy('jam')
            ->take(5)
            ->get();

        // Low Stock Items
        $lowStockItems = Sparepart::whereColumn('stok', '<', 'stok_minimum')
            ->orderBy('stok')
            ->take(5)
            ->get();

        // Chart: Pendapatan 7 Hari Terakhir
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D');
            $chartData[] = PosTransaction::whereDate('created_at', $date)
                ->where('status_pembayaran', 'Lunas')
                ->sum('total');
        }

        // Chart: Jasa vs Sparepart
        $totalJasa = PosItem::where('item_type', 'jasa')->sum('subtotal');
        $totalSparepart = PosItem::where('item_type', 'sparepart')->sum('subtotal');

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'bookingHariIni',
            'stokMenipis',
            'ratingRataRata',
            'bookingsToday',
            'lowStockItems',
            'chartData',
            'chartLabels',
            'totalJasa',
            'totalSparepart'
        ));
    }
}
