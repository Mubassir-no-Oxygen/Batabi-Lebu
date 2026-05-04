<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReviewReceivedNotification;

class ReviewController extends Controller
{
    /**
     * Show the review form for a completed order.
     * Only the buyer who placed the order can access this.
     */
    public function create(Order $order)
    {
        $buyer = Auth::user()->buyer;

        // Ensure this order belongs to this buyer
        if ($order->buyer_id !== $buyer->id) {
            abort(403, 'You cannot review this order.');
        }

        // Only accepted orders can be reviewed
        if ($order->status !== 'accepted') {
            return redirect()->route('buyer.orders.index')
                ->with('error', 'You can only review accepted orders.');
        }

        // Prevent double reviewing
        if ($order->review) {
            return redirect()->route('buyer.orders.index')
                ->with('info', 'You have already submitted a review for this order.');
        }

        $order->load('crop.farmer.user');

        return view('buyer.reviews.create', compact('order'));
    }

    /**
     * Store a new review submitted by the buyer.
     */
    public function store(Request $request, Order $order)
    {
        $buyer = Auth::user()->buyer;

        // Authorization checks
        if ($order->buyer_id !== $buyer->id) {
            abort(403, 'You cannot review this order.');
        }

        if ($order->status !== 'accepted') {
            return redirect()->route('buyer.orders.index')
                ->with('error', 'You can only review accepted orders.');
        }

        if ($order->review) {
            return redirect()->route('buyer.orders.index')
                ->with('info', 'You have already reviewed this order.');
        }

        // Validate input
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $farmerUserId = $order->crop->farmer->user_id;

        $review = Review::create([
            'order_id'    => $order->id,
            'reviewer_id' => Auth::id(),          // the buyer's user ID
            'reviewee_id' => $farmerUserId,        // the farmer's user ID
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'] ?? null,
        ]);

        // Notify Farmer
        $order->crop->farmer->user->notify(new ReviewReceivedNotification($review));

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Thank you! Your review has been submitted.');
    }

    /**
     * Show all reviews received by a specific farmer (public profile).
     */
    public function farmerReviews(Farmer $farmer)
    {
        $farmer->load('user');

        $reviews = Review::where('reviewee_id', $farmer->user_id)
            ->with(['reviewer', 'order.crop'])
            ->latest()
            ->paginate(10);

        // Calculate average rating
        $avgRating = Review::where('reviewee_id', $farmer->user_id)->avg('rating');

        return view('farmer.reviews.index', compact('farmer', 'reviews', 'avgRating'));
    }

    /**
     * Farmer dashboard: view all reviews they have received.
     */
    public function myReviews()
    {
        $farmer  = Auth::user()->farmer;
        $reviews = Review::where('reviewee_id', Auth::id())
            ->with(['reviewer', 'order.crop'])
            ->latest()
            ->paginate(10);

        $avgRating = Review::where('reviewee_id', Auth::id())->avg('rating');

        return view('farmer.reviews.my', compact('reviews', 'avgRating', 'farmer'));
    }
}
