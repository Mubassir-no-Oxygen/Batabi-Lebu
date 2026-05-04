<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** FUTURE FEATURE: Supplier marketplace for seeds/fertilizers. Structure ready. */
class Product extends Model {
    protected $fillable = ['supplier_id','product_name','category','description','price','unit','stock_quantity','image','status'];
    public function supplier() { return $this->belongsTo(User::class, 'supplier_id'); }
}
