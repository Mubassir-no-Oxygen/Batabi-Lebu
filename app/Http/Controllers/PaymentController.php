<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Order $order)
{
    abort_if($order->status !== 'accepted', 403, 'Order is not accepted yet.');
    return view('payments.create', compact('order'));
}
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:bkash,nagad,bank,cash',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        if (Payment::where('order_id', $order->id)->exists()) {
            return redirect()->back()->with('error', 'Payment already submitted for this order.');
        }

        Payment::create([
            'order_id'       => $order->id,
            'amount'         => $order->final_price * $order->requested_quantity,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'escrow_status'  => 'held',
            'transaction_id' => $request->transaction_id,
            'paid_at'        => now(),
        ]);

        return redirect()->route('payment.show', $order)
                         ->with('success', 'Payment held securely until delivery confirmed.');
    }

    public function show(Order $order)
    {
        $payment = Payment::where('order_id', $order->id)->firstOrFail();
        return view('payments.show', compact('order', 'payment'));
    }



    public function release(Order $order)
     {
          $payment = Payment::where('order_id', $order->id)->firstOrFail();
    
          if ($payment->escrow_status !== 'held') {
                return redirect()->back()->with('error', 'Payment is not in escrow.');
          }
    
          $payment->update(['escrow_status' => 'released']);
          return redirect()->route('payment.show', $order)->with('success', 'Payment released to seller.');
     }

}