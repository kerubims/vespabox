<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // For MVP, we can retrieve the active booking of the user
        $activeBooking = Booking::where('user_id', Auth::id() ?? 2)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->latest()
            ->first();

        return view('customer.dashboard', compact('activeBooking'));
    }
}
