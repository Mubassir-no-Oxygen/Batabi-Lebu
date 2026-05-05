<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Payment/escrow. Structure ready, feature not yet implemented. */
class Payment extends Model {
    protected $fillable = ['order_id','amount','payment_method','payment_status','escrow_status','transaction_id','paid_at'];
    public function order() { return $this->belongsTo(Order::class); }
}
