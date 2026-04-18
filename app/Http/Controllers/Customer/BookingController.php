<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slot;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        return view('customer.booking.create');
    }

    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json([]);
        }

        $carbonDate = Carbon::parse($date);
        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $dayName = $dayNames[$carbonDate->dayOfWeek];

        // Get slots for this day
        $slots = Slot::where('hari', $dayName)
                    ->where('is_active', true)
                    ->orderBy('jam')
                    ->get();

        // Get booking counts for this date
        $bookings = Booking::whereDate('tanggal', $date)
                          ->select('jam', \DB::raw('count(*) as total'))
                          ->groupBy('jam')
                          ->get()
                          ->pluck('total', 'jam');

        $availability = $slots->map(function($slot) use ($bookings) {
            $jamString = Carbon::parse($slot->jam)->format('H:i');
            $bookedCount = $bookings[$jamString] ?? 0;
            return [
                'time' => $jamString,
                'available' => $bookedCount < $slot->kapasitas,
                'remaining' => max(0, $slot->kapasitas - $bookedCount)
            ];
        });

        return response()->json($availability);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|string',
            'kendaraan' => 'required|string',
            'keluhan' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required|date_format:H:i',
        ]);

        // Check Capacity
        $carbonDate = Carbon::parse($validated['tanggal']);
        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $dayName = $dayNames[$carbonDate->dayOfWeek];

        $slot = Slot::where('hari', $dayName)
                    ->where('jam', $validated['jam'])
                    ->where('is_active', true)
                    ->first();

        if (!$slot) {
            return back()->withErrors(['jam' => 'Slot tidak tersedia.'])->withInput();
        }

        $bookedCount = Booking::whereDate('tanggal', $validated['tanggal'])
                              ->where('jam', $validated['jam'])
                              ->count();

        if ($bookedCount >= $slot->kapasitas) {
            return back()->withErrors(['jam' => 'Maaf, slot ini sudah penuh. Silakan pilih jam lain.'])->withInput();
        }

        // Generate Booking Code
        $code = 'VB-' . strtoupper(substr(md5(uniqid()), 0, 6));

        $booking = Booking::create([
            'kode_booking' => $code,
            'user_id' => Auth::id(),
            'plat_nomor' => $validated['plat_nomor'],
            'kendaraan' => $validated['kendaraan'],
            'keluhan' => $validated['keluhan'],
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'status' => 'Menunggu',
        ]);

        // Send Notification to all Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewBookingNotification($booking));

        \App\Events\BookingCreated::dispatch('Booking baru: ' . $booking->kode_booking);
        \App\Events\QueueUpdated::dispatch('Antrean bertambah');

        return redirect()->route('customer.booking.success')->with('kode', $booking->kode_booking);
    }

    public function success()
    {
        $kode = session('kode');
        if (!$kode) {
            return redirect()->route('customer.booking.create');
        }

        $booking = \App\Models\Booking::where('kode_booking', $kode)->firstOrFail();
        return view('customer.booking.success', compact('booking'));
    }
}
