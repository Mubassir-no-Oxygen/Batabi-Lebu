<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyRequestController extends Controller
{
    public function index()
    {
        $farmer = Auth::user()->farmer;
        
        // Fetch farmer's past emergency requests
        $requests = EmergencyRequest::where('farmer_id', $farmer->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
        return view('farmer.emergency.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:weather,pest,finance,other',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        EmergencyRequest::create([
            'farmer_id' => Auth::user()->farmer->id,
            'type' => $request->type,
            'description' => $request->description,
            'location' => $request->location,
            'status' => 'open'
        ]);

        return redirect()->route('farmer.emergency.index')->with('success', 'Emergency support request has been sent successfully. Support team will review it shortly.');
    }
}
