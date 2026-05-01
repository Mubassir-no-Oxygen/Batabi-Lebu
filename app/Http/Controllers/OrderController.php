<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ─── Buyer: place an order ────────────────────────────────
    public function store(Request $request, Crop $crop)
    {
        $validated = $request->validate([
            'requested_quantity' => 'required|numeric|min:0.1|max:' . $crop->quantity,
            'offered_price'      => 'nullable|numeric|min:0',
            'note'               => 'nullable|string|max:500',
        ]);

        $buyer = Auth::user()->buyer;

        // Prevent duplicate pending orders by same buyer on same crop
        $existing = Order::where('buyer_id', $buyer->id)
            ->where('crop_id', $crop->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending order for this crop.');
        }

        Order::create([
            'buyer_id'           => $buyer->id,
            'crop_id'            => $crop->id,
            'requested_quantity' => $validated['requested_quantity'],
            'offered_price'      => $validated['offered_price'] ?? null,
            'note'               => $validated['note'] ?? null,
            'status'             => 'pending',
        ]);

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Order request submitted! Waiting for farmer response.');
    }

    // ─── Buyer: view order history ────────────────────────────
    public function buyerOrders()
    {
        $buyer  = Auth::user()->buyer;
        $orders = $buyer->orders()->with('crop.farmer.user', 'review')->latest()->paginate(10);
        return view('buyer.orders.index', compact('orders'));
    }

    // ─── Farmer: view incoming orders ────────────────────────
    public function farmerOrders()
    {
        $farmer = Auth::user()->farmer;
        $orders = Order::whereHas('crop', fn($q) => $q->where('farmer_id', $farmer->id))
            ->with('crop', 'buyer.user')
            ->latest()
            ->paginate(10);
        return view('farmer.orders.index', compact('orders'));
    }

    // ─── Farmer: accept an order ──────────────────────────────
    public function accept(Order $order)
    {
        $this->authorizeOrder($order);

        $order->update([
            'status'      => 'accepted',
            'final_price' => $order->offered_price ?? $order->crop->price_per_unit,
            'accepted_at' => now(),
        ]);

        return back()->with('success', 'Order accepted successfully!');
    }

    // ─── Farmer: reject an order ──────────────────────────────
    public function reject(Order $order)
    {
        $this->authorizeOrder($order);
        $order->update(['status' => 'rejected']);
        return back()->with('success', 'Order rejected.');
    }

    /**
     * Ensure the order's crop belongs to the authenticated farmer.
     */
    private function authorizeOrder(Order $order): void
    {
        $farmer = Auth::user()->farmer;
        if ($order->crop->farmer_id !== $farmer->id) {
            abort(403, 'You do not own this order.');
        }
    }
}
