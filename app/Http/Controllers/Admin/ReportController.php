<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosTransaction;
use App\Models\PosItem;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from', Carbon::now()->startOfMonth()->toDateString());
        $to = $request->input('to', Carbon::today()->toDateString());
        $jenis = $request->input('jenis', 'pendapatan');

        $transactions = PosTransaction::with('booking.user', 'items')
            ->where('status_pembayaran', 'Lunas')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate totals
        $totalPendapatan = $transactions->sum('total');
        $totalJasa = 0;
        $totalSparepart = 0;

        foreach ($transactions as $trx) {
            foreach ($trx->items as $item) {
                if ($item->item_type === 'jasa') {
                    $totalJasa += $item->subtotal;
                } else {
                    $totalSparepart += $item->subtotal;
                }
            }
        }

        return view('admin.laporan.index', compact(
            'transactions',
            'totalPendapatan',
            'totalJasa',
            'totalSparepart',
            'from',
            'to',
            'jenis'
        ));
    }
}
