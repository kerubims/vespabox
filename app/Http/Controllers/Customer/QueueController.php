<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $bookings = Booking::with('user')
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Menunggu', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Selesai'])
            ->orderByRaw("FIELD(status, 'Selesai', 'Sedang Dikerjakan', 'Dikonfirmasi', 'Menunggu')")
            ->orderBy('jam')
            ->get();

        // Find current user's booking
        $myBooking = $bookings->where('user_id', Auth::id())->first();

        return view('customer.antrean', compact('bookings', 'myBooking'));
    }
}
