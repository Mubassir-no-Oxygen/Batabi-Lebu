<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Delivery tracking. Structure ready, feature not yet implemented. */
class Delivery extends Model {
    protected $fillable = ['order_id','delivery_partner_id','pickup_address','delivery_address','status','tracking_number','expected_at','delivered_at'];
    public function order() { return $this->belongsTo(Order::class); }
    public function partner() { return $this->belongsTo(User::class, 'delivery_partner_id'); }
}
