<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Advisory;
use Illuminate\Http\Request;

class AdvisoryController extends Controller
{
    public function index()
    {
        // For farmers, get active advisories. 
        // In a real system, we would filter by 'target_district' matching farmer's district if set.
        $advisories = Advisory::where('is_active', 1)
                        ->where(function($query) {
                            $query->whereNull('expires_at')
                                  ->orWhere('expires_at', '>', now());
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
                        
        return view('farmer.advisories.index', compact('advisories'));
    }
}
