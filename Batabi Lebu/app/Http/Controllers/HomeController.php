<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Farmer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Show recent available crop listings for homepage
        $featuredCrops = Crop::with('farmer.user')
            ->where('status', 'available')
            ->whereHas('farmer', fn($q) => $q->where('verification_status', 'approved'))
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'farmers' => Farmer::where('verification_status', 'approved')->count(),
            'crops'   => Crop::where('status', 'available')->count(),
        ];

        return view('home', compact('featuredCrops', 'stats'));
    }
}
