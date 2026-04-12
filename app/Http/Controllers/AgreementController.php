<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Order;
use App\Models\Buyer;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgreementController extends Controller
{
    

    public function generate(Order $order): Agreement
    {
        if ($order->agreement) return $order->agreement;

        $price    = $order->final_price ?? $order->crop->price_per_unit;
        $discount = $order->bulk_discount_percent ?? 0;
        $subtotal = $price * $order->requested_quantity;
        $total    = $subtotal - ($subtotal * $discount / 100);

        $terms = "1. The farmer agrees to supply {$order->requested_quantity} {$order->crop->unit} of {$order->crop->crop_name}.\n"
            . "2. The buyer agrees to pay ৳" . number_format($total, 2) . " upon successful delivery.\n"
            . "3. Payment will be held securely by the platform until delivery is confirmed.\n"
            . "4. Both parties agree to resolve disputes through the platform's complaint system.\n"
            . "5. Quality disputes must be raised within 24 hours of delivery.";

        return Agreement::create([
            'order_id'              => $order->id,
            'farmer_id'             => $order->crop->farmer_id,
            'buyer_id'              => $order->buyer_id,
            'agreed_quantity'       => $order->requested_quantity,
            'quantity_unit'         => $order->crop->unit,
            'agreed_price_per_unit' => $price,
            'total_amount'          => $total,
            'bulk_discount_percent' => $discount,
            'terms'                 => $terms,
            'status'                => 'pending_farmer',
        ]);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'farmer') {
            $farmer     = Farmer::where('user_id', $user->id)->firstOrFail();
            $agreements = Agreement::with(['buyer.user', 'order.crop'])
                ->where('farmer_id', $farmer->id)->latest()->paginate(10);
        } elseif ($user->role === 'buyer') {
            $buyer      = Buyer::where('user_id', $user->id)->firstOrFail();
            $agreements = Agreement::with(['farmer.user', 'order.crop'])
                ->where('buyer_id', $buyer->id)->latest()->paginate(10);
        } else {
            $agreements = Agreement::with(['farmer.user', 'buyer.user'])->latest()->paginate(15);
        }

        return view('agreements.index', compact('agreements'));
    }

    public function show(Agreement $agreement)
    {
        $this->authorizeAccess($agreement);
        $agreement->load(['farmer.user', 'buyer.user', 'order.crop']);
        return view('agreements.show', compact('agreement'));
    }

    public function sign(Agreement $agreement)
    {
        $user = Auth::user();
        $this->authorizeAccess($agreement);
        abort_if($agreement->status === 'cancelled', 400);

        $farmer = Farmer::where('user_id', $user->id)->first();
        $buyer  = Buyer::where('user_id', $user->id)->first();

        if ($farmer && $agreement->farmer_id === $farmer->id && !$agreement->farmer_signed_at) {
            $agreement->update([
                'farmer_signed_at' => now(),
                'status'           => $agreement->buyer_signed_at ? 'signed' : 'pending_buyer',
            ]);
        } elseif ($buyer && $agreement->buyer_id === $buyer->id && !$agreement->buyer_signed_at) {
            $agreement->update([
                'buyer_signed_at' => now(),
                'status'          => $agreement->farmer_signed_at ? 'signed' : 'pending_farmer',
            ]);
        }

        return redirect()->route('agreements.show', $agreement)
            ->with('success', 'Agreement signed successfully!');
    }

    private function authorizeAccess(Agreement $agreement): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;

        $farmer = Farmer::where('user_id', $user->id)->first();
        $buyer  = Buyer::where('user_id', $user->id)->first();

        $isFarmer = $farmer && $agreement->farmer_id === $farmer->id;
        $isBuyer  = $buyer  && $agreement->buyer_id  === $buyer->id;

        abort_if(!$isFarmer && !$isBuyer, 403);
    }
}
