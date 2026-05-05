<?php

namespace App\Http\Controllers;

use App\Models\EmergencyRequest;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyRequestController extends Controller
{
    // Farmer: view own requests + form
    public function index()
    {
        $farmer = Farmer::where('user_id', Auth::id())->firstOrFail();

        $requests = EmergencyRequest::where('farmer_id', $farmer->id)
            ->latest()
            ->get();

        return view('emergency.index', compact('requests'));
    }

    // Farmer: submit new emergency request
    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:weather,pest,finance,other',
            'description' => 'required|string|min:20|max:1000',
            'location'    => 'nullable|string|max:255',
        ]);

        $farmer = Farmer::where('user_id', Auth::id())->firstOrFail();

        EmergencyRequest::create([
            'farmer_id'   => $farmer->id,
            'type'        => $request->type,
            'description' => $request->description,
            'location'    => $request->location,
            'status'      => 'open',
        ]);

        return redirect()->route('emergency.index')
            ->with('success', 'Emergency request submitted successfully!');
    }

    // Admin: view all requests
    public function adminIndex()
    {
        $requests = EmergencyRequest::with('farmer.user')
            ->latest()
            ->get();

        return view('admin.emergency.index', compact('requests'));
    }

    // Admin: update status
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|in:open,in_review,resolved',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $emergencyRequest = EmergencyRequest::findOrFail($id);

        $emergencyRequest->update([
            'status'      => $request->status,
            'admin_note'  => $request->admin_note,
            'resolved_at' => $request->status === 'resolved' ? now() : null,
        ]);

        return redirect()->route('admin.emergency.index')
            ->with('success', 'Emergency request updated!');
    }
}
