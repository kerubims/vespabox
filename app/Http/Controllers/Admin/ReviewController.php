<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user', 'booking')->where('is_hidden', false);

        if ($request->filled('filter')) {
            if ($request->filter === '5star') {
                $query->where('rating', 5);
            } elseif ($request->filter === 'low') {
                $query->where('rating', '<', 4);
            } elseif ($request->filter === 'unreplied') {
                $query->whereNull('admin_reply');
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $avgRating = Review::where('is_hidden', false)->avg('rating') ?? 0;
        $totalReviews = Review::where('is_hidden', false)->count();
        $unrepliedCount = Review::where('is_hidden', false)->whereNull('admin_reply')->count();

        return view('admin.review.index', compact('reviews', 'avgRating', 'totalReviews', 'unrepliedCount'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ], [
            'admin_reply.required' => 'Balasan wajib diisi.',
            'admin_reply.max'      => 'Balasan tidak boleh lebih dari 1000 karakter.',
        ]);

        $review = Review::findOrFail($id);
        $review->update(['admin_reply' => $request->admin_reply]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Balasan berhasil disimpan']);
        }

        return back()->with('success', 'Balasan berhasil disimpan');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Sembunyikan ulasan dari admin dan publik
        $review->update(['is_hidden' => true]);

        return back()->with('success', 'Ulasan berhasil disembunyikan');
    }
}
