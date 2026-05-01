<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerController extends Controller
{
    /**
     * Farmer dashboard — summary stats + recent orders.
     */
    public function dashboard()
    {
        $farmer = Auth::user()->farmer;
        $recentOrders = Order::whereHas('crop', fn($q) => $q->where('farmer_id', $farmer->id))
            ->with('crop', 'buyer.user')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_crops'    => $farmer->crops()->count(),
            'active_crops'   => $farmer->crops()->where('status', 'available')->count(),
            'pending_orders' => Order::whereHas('crop', fn($q) => $q->where('farmer_id', $farmer->id))
                ->where('status', 'pending')->count(),
            'accepted_orders'=> Order::whereHas('crop', fn($q) => $q->where('farmer_id', $farmer->id))
                ->where('status', 'accepted')->count(),
            'avg_rating'     => Review::where('reviewee_id', Auth::id())->avg('rating'),
            'total_reviews'  => Review::where('reviewee_id', Auth::id())->count(),
        ];

        return view('farmer.dashboard', compact('recentOrders', 'stats'));
    }

    /**
     * Registration pending page.
     */
    public function pending()
    {
        return view('farmer.pending');
    }

    /**
     * Registration rejected page.
     */
    public function rejected()
    {
        return view('farmer.rejected');
    }
}
