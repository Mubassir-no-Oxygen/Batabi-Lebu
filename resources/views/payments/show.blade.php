Good news — payment was saved! ✅ Now it's redirecting to `show` page but `payments/show.blade.php` is trying to check `auth()->user()->buyer` which is null.

---

## Fix — Update `payments/show.blade.php`

Open `resources/views/payments/show.blade.php` and replace everything with:

```blade
@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 600px">
    <h4>💰 Payment Status – Order #{{ $order->id }}</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4">
        <p><strong>Amount:</strong> ৳{{ number_format($payment->amount, 2) }}</p>
        <p><strong>Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
        <p><strong>Paid At:</strong> {{ $payment->paid_at }}</p>

        <p><strong>Escrow Status:</strong>
            @if($payment->escrow_status === 'held')
                <span class="badge bg-warning text-dark">🔒 Held</span>
            @elseif($payment->escrow_status === 'released')
                <span class="badge bg-success">✅ Released to Farmer</span>
            @else
                <span class="badge bg-danger">↩ Refunded</span>
            @endif
        </p>
    </div>

    @if($payment->escrow_status === 'held')
    <form method="POST" action="{{ route('payment.release', $order) }}" class="mt-3">
        @csrf
        <button class="btn btn-success w-100"
                onclick="return confirm('Confirm delivery received? This will release payment to the farmer.')">
            ✅ Confirm Delivery & Release Payment
        </button>
    </form>
    @endif
</div>
@endsection
```

Save **Ctrl+S** then refresh!