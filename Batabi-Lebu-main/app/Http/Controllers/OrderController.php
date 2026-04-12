<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Crop;
use App\Models\Buyer;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'buyer') {
            $buyer  = Buyer::where('user_id', $user->id)->firstOrFail();
            $orders = Order::with(['crop.farmer.user', 'latestNegotiation', 'agreement'])
                ->where('buyer_id', $buyer->id)->latest()->paginate(10);

        } elseif ($user->role === 'farmer') {
            $farmer  = Farmer::where('user_id', $user->id)->firstOrFail();
            $cropIds = Crop::where('farmer_id', $farmer->id)->pluck('id');
            $orders  = Order::with(['buyer.user', 'crop', 'latestNegotiation', 'agreement'])
                ->whereIn('crop_id', $cropIds)->latest()->paginate(10);

        } else {
            $orders = Order::with(['buyer.user', 'crop.farmer.user', 'agreement'])
                ->latest()->paginate(15);
        }

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(Auth::user()->role !== 'buyer', 403);
        $crops = Crop::with('farmer.user')
            ->where('status', 'available')
            ->whereHas('farmer', fn($q) => $q->where('verification_status', 'approved'))
            ->get();
        return view('orders.create', compact('crops'));
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'buyer', 403);

        $validated = $request->validate([
            'crop_id'            => 'required|exists:crops,id',
            'requested_quantity' => 'required|numeric|min:1',
            'offered_price'      => 'nullable|numeric|min:0',
            'note'               => 'nullable|string|max:1000',
        ]);

        $buyer = Buyer::where('user_id', Auth::id())->firstOrFail();
        $validated['buyer_id'] = $buyer->id;
        $validated['status']   = 'pending';

        $order = Order::create($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order request sent to farmer!');
    }

    public function show(Order $order)
    {
        $this->authorizeAccess($order);
        $order->load(['buyer.user', 'crop.farmer.user', 'negotiations.sender', 'negotiations.receiver', 'agreement']);
        return view('orders.show', compact('order'));
    }

    public function accept(Order $order)
    {
        $this->authorizeFarmer($order);
        abort_if($order->status !== 'pending', 400);

        $price = $order->latestNegotiation?->proposed_price ?? $order->offered_price ?? $order->crop->price_per_unit;

        $order->update([
            'status'      => 'accepted',
            'final_price' => $price,
            'accepted_at' => now(),
        ]);

        app(AgreementController::class)->generate($order);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order accepted! Agreement generated.');
    }

    public function cancel(Order $order)
    {
        $this->authorizeAccess($order);
        abort_if(in_array($order->status, ['completed', 'cancelled']), 400);
        $order->update(['status' => 'cancelled']);
        return redirect()->route('orders.index')->with('success', 'Order cancelled.');
    }

    private function authorizeAccess(Order $order): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;

        if ($user->role === 'buyer') {
            $buyer = Buyer::where('user_id', $user->id)->first();
            abort_if($order->buyer_id !== $buyer?->id, 403);
        } elseif ($user->role === 'farmer') {
            $farmer  = Farmer::where('user_id', $user->id)->first();
            $cropIds = Crop::where('farmer_id', $farmer?->id)->pluck('id');
            abort_if(!$cropIds->contains($order->crop_id), 403);
        }
    }

    private function authorizeFarmer(Order $order): void
    {
        abort_if(Auth::user()->role !== 'farmer', 403);
        $farmer  = Farmer::where('user_id', Auth::id())->firstOrFail();
        $cropIds = Crop::where('farmer_id', $farmer->id)->pluck('id');
        abort_if(!$cropIds->contains($order->crop_id), 403);
    }
}
