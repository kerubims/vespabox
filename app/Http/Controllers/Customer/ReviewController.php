<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($bookingId = null)
    {
        $booking = null;

        if ($bookingId) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->where('status', 'Selesai')
                ->where('is_reviewed', false)
                ->firstOrFail();
        }

        return view('customer.review.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'booking_id.required' => 'Data booking tidak ditemukan.',
            'booking_id.exists'   => 'Booking yang dipilih tidak valid.',
            'rating.required'     => 'Silakan berikan rating terlebih dahulu.',
            'rating.integer'      => 'Rating harus berupa angka.',
            'rating.min'          => 'Rating minimal adalah 1 bintang.',
            'rating.max'          => 'Rating maksimal adalah 5 bintang.',
            'comment.max'         => 'Komentar tidak boleh lebih dari 1000 karakter.',
        ]);

        // Verify the booking belongs to the user and is completed
        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', Auth::id())
            ->where('status', 'Selesai')
            ->where('is_reviewed', false)
            ->firstOrFail();

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        $booking->update(['is_reviewed' => true]);

        return redirect()->route('customer.riwayat')
            ->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
