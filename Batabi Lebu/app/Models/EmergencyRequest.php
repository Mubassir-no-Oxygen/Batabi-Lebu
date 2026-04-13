<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Emergency support for farmers. Structure ready. */
class EmergencyRequest extends Model {
    protected $fillable = ['farmer_id','type','description','location','status','admin_note','resolved_at'];
    public function farmer() { return $this->belongsTo(Farmer::class); }
}
