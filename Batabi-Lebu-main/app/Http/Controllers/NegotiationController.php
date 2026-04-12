<?php

namespace App\Http\Controllers;

use App\Models\Negotiation;
use App\Models\Order;
use App\Models\Buyer;
use App\Models\Farmer;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
   

    public function store(Request $request, Order $order)
    {
        [$senderId, $receiverId] = $this->getSenderReceiver($order);

        abort_if(in_array($order->status, ['accepted', 'completed', 'cancelled', 'rejected']), 400, 'Negotiation closed.');

        $validated = $request->validate([
            'proposed_price' => 'required|numeric|min:0',
            'message'        => 'nullable|string|max:500',
        ]);

        $order->negotiations()->where('status', 'pending')->update(['status' => 'countered']);

        Negotiation::create([
            'order_id'       => $order->id,
            'sender_id'      => $senderId,
            'receiver_id'    => $receiverId,
            'proposed_price' => $validated['proposed_price'],
            'message'        => $validated['message'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Counter-offer sent!');
    }

    public function accept(Order $order)
    {
        $latest = $order->latestNegotiation;
        abort_if(!$latest || $latest->receiver_id !== Auth::id(), 403);

        $latest->update(['status' => 'accepted']);
        $order->update([
            'status'      => 'accepted',
            'final_price' => $latest->proposed_price,
            'accepted_at' => now(),
        ]);

        app(AgreementController::class)->generate($order);

        return redirect()->route('orders.show', $order)->with('success', 'Offer accepted! Agreement generated.');
    }

    public function reject(Order $order)
    {
        $latest = $order->latestNegotiation;
        abort_if(!$latest || $latest->receiver_id !== Auth::id(), 403);
        $latest->update(['status' => 'rejected']);
        return redirect()->route('orders.show', $order)->with('info', 'Offer rejected.');
    }

    private function getSenderReceiver(Order $order): array
    {
        $user = Auth::user();

        if ($user->role === 'buyer') {
            $buyer = Buyer::where('user_id', $user->id)->firstOrFail();
            abort_if($order->buyer_id !== $buyer->id, 403);
            $farmerUserId = $order->crop->farmer->user_id;
            return [$user->id, $farmerUserId];
        }

        if ($user->role === 'farmer') {
            $farmer  = Farmer::where('user_id', $user->id)->firstOrFail();
            $cropIds = Crop::where('farmer_id', $farmer->id)->pluck('id');
            abort_if(!$cropIds->contains($order->crop_id), 403);
            $buyerUserId = $order->buyer->user_id;
            return [$user->id, $buyerUserId];
        }

        abort(403);
    }
}
