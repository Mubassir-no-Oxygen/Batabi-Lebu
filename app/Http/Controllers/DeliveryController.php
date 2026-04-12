<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // Admin: list all deliveries
    // Delivery partner: list their assigned deliveries
    // Farmer/Buyer: list deliveries for their orders
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $deliveries = Delivery::with(['order.crop', 'order.buyer.user', 'deliveryPartner'])
                ->latest()->paginate(15);

        } elseif ($user->role === 'delivery_partner') {
            $deliveries = Delivery::with(['order.crop', 'order.buyer.user'])
                ->where('delivery_partner_id', $user->id)
                ->latest()->paginate(10);

        } elseif ($user->role === 'farmer') {
            $deliveries = Delivery::with(['order.crop', 'order.buyer.user', 'deliveryPartner'])
                ->whereHas('order.crop.farmer', fn($q) => $q->where('user_id', $user->id))
                ->latest()->paginate(10);

        } else {
            // buyer
            $deliveries = Delivery::with(['order.crop', 'deliveryPartner'])
                ->whereHas('order.buyer', fn($q) => $q->where('user_id', $user->id))
                ->latest()->paginate(10);
        }

        return view('deliveries.index', compact('deliveries'));
    }

    // Admin: show assign delivery partner form for an order
    public function create(Order $order)
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        abort_if($order->status !== 'accepted', 400, 'Order must be accepted before assigning delivery.');
        abort_if($order->delivery()->exists(), 400, 'Delivery already assigned for this order.');

        $partners = User::where('role', 'delivery_partner')->get();
        return view('deliveries.create', compact('order', 'partners'));
    }

    // Admin: assign delivery partner
    public function store(Request $request, Order $order)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $validated = $request->validate([
            'delivery_partner_id' => 'nullable|exists:users,id',
            'pickup_address'      => 'required|string',
            'delivery_address'    => 'required|string',
            'expected_at'         => 'nullable|date|after:now',
        ]);

        $delivery = Delivery::create([
            'order_id'            => $order->id,
            'delivery_partner_id' => $validated['delivery_partner_id'] ?? null,
            'pickup_address'      => $validated['pickup_address'],
            'delivery_address'    => $validated['delivery_address'],
            'expected_at'         => $validated['expected_at'] ?? null,
            'status'              => 'pending',
            'tracking_number'     => Delivery::generateTrackingNumber(),
        ]);

        return redirect()->route('deliveries.show', $delivery)
            ->with('success', 'Delivery assigned! Tracking: ' . $delivery->tracking_number);
    }

    // Show delivery details
    public function show(Delivery $delivery)
    {
        $this->authorizeAccess($delivery);
        $delivery->load(['order.crop.farmer.user', 'order.buyer.user', 'deliveryPartner']);
        return view('deliveries.show', compact('delivery'));
    }

    // Delivery partner: update delivery status
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $user = Auth::user();
        abort_if(
            $user->role !== 'delivery_partner' && $user->role !== 'admin',
            403
        );
        abort_if(
            $user->role === 'delivery_partner' && $delivery->delivery_partner_id !== $user->id,
            403
        );

        $validated = $request->validate([
            'status' => 'required|in:pending,picked_up,in_transit,delivered,returned',
        ]);

        $data = ['status' => $validated['status']];

        if ($validated['status'] === 'delivered') {
            $data['delivered_at'] = now();
            // Mark order as completed
            $delivery->order->update(['status' => 'completed', 'completed_at' => now()]);
        }

        $delivery->update($data);

        return redirect()->route('deliveries.show', $delivery)
            ->with('success', 'Delivery status updated to: ' . ucfirst(str_replace('_', ' ', $validated['status'])));
    }

    private function authorizeAccess(Delivery $delivery): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'delivery_partner' && $delivery->delivery_partner_id === $user->id) return;

        // Check if farmer or buyer of this order
        $order = $delivery->order;
        $isFarmerOwner = $order->crop->farmer->user_id === $user->id;
        $isBuyerOwner  = $order->buyer->user_id === $user->id;
        abort_if(!$isFarmerOwner && !$isBuyerOwner, 403);
    }
}
