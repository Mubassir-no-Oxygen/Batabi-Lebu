<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Price negotiation. Structure ready, feature not yet implemented. */
class Negotiation extends Model {
    protected $fillable = ['order_id','sender_id','receiver_id','proposed_price','message','status'];
    public function order() { return $this->belongsTo(Order::class); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }
}
