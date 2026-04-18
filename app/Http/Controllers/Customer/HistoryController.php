<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Riwayat Booking (Active)
        $activeBookings = Booking::with('user')
            ->where('user_id', $userId)
            ->whereIn('status', ['Menunggu', 'Dikonfirmasi'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get();

        // Riwayat Service (Finished/Canceled)
        $serviceHistory = Booking::with('user', 'transaction.items', 'review')
            ->where('user_id', $userId)
            ->whereIn('status', ['Selesai', 'Dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('customer.riwayat', compact('activeBookings', 'serviceHistory'));
    }
}
