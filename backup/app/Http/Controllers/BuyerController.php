<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Farmer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Buyer dashboard — show summary stats.
     */
    public function dashboard()
    {
        $buyer  = auth()->user()->buyer;
        $orders = $buyer->orders()->with('crop')->latest()->take(5)->get();

        $stats = [
            'total'    => $buyer->orders()->count(),
            'pending'  => $buyer->orders()->where('status', 'pending')->count(),
            'accepted' => $buyer->orders()->where('status', 'accepted')->count(),
        ];

        return view('buyer.dashboard', compact('orders', 'stats'));
    }

    /**
     * Browse/search crop listings.
     */
    public function browseCrops(Request $request)
    {
        $query = Crop::with('farmer.user')
            ->where('status', 'available')
            ->whereHas('farmer', fn($q) => $q->where('verification_status', 'approved'));

        // Filter: category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter: district (via farmer)
        if ($request->filled('district')) {
            $query->whereHas('farmer', fn($q) =>
                $q->where('district', 'like', '%' . $request->district . '%')
            );
        }

        // Filter: price range
        if ($request->filled('min_price')) {
            $query->where('price_per_unit', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_unit', '<=', $request->max_price);
        }

        // Search by crop name
        if ($request->filled('search')) {
            $query->where('crop_name', 'like', '%' . $request->search . '%');
        }

        $crops    = $query->latest()->paginate(12)->withQueryString();
        $districts = Farmer::where('verification_status', 'approved')->distinct()->pluck('district');

        return view('buyer.crops.index', compact('crops', 'districts'));
    }

    /**
     * Show crop detail + order form.
     */
    public function showCrop(Crop $crop)
    {
        $crop->load('farmer.user');
        return view('buyer.crops.show', compact('crop'));
    }
}
