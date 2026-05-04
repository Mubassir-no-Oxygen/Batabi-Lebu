<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Complaint system. Structure ready, feature not yet implemented. */
class Complaint extends Model {
    protected $fillable = ['user_id','order_id','subject','description','status','admin_note','resolved_at'];
    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
