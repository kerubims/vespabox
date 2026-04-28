<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'booking'])
            ->where('rating', '>=', 0)
            ->where('is_hidden', false)
            ->latest()
            ->get();

        $stats = [
            'total_servis' => \App\Models\Booking::where('status', 'Selesai')->count(),
            'avg_rating' => round(\App\Models\Review::where('is_hidden', false)->avg('rating') ?? 5.0, 1),
            'total_reviews' => \App\Models\Review::where('is_hidden', false)->count()
        ];

        return view('home', compact('reviews', 'stats'));
    }

    public function katalog(Request $request)
    {
        $query = Sparepart::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $spareparts = $query->paginate(12);
        $categories = Sparepart::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('katalog', compact('spareparts', 'categories'));
    }
}
