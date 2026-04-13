<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\User;
use App\Models\Order;
use App\Models\Crop;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard — platform-wide stats.
     */
    public function dashboard()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_farmers'     => Farmer::count(),
            'pending_farmers'   => Farmer::where('verification_status', 'pending')->count(),
            'approved_farmers'  => Farmer::where('verification_status', 'approved')->count(),
            'total_crops'       => Crop::count(),
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
        ];

        $recentFarmers = Farmer::with('user')
            ->where('verification_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentFarmers'));
    }

    /**
     * List all farmers with verification filter.
     */
    public function farmers(Request $request)
    {
        $status  = $request->get('status', 'pending');
        $farmers = Farmer::with('user')
            ->when($status !== 'all', fn($q) => $q->where('verification_status', $status))
            ->latest()
            ->paginate(15);

        return view('admin.farmers.index', compact('farmers', 'status'));
    }

    /**
     * Show farmer detail.
     */
    public function showFarmer(Farmer $farmer)
    {
        $farmer->load('user', 'crops');
        return view('admin.farmers.show', compact('farmer'));
    }

    /**
     * Approve a farmer.
     */
    public function approveFarmer(Farmer $farmer)
    {
        $farmer->update([
            'verification_status' => 'approved',
            'verified_at'         => now(),
            'verified_by'         => auth()->id(),
            'rejection_reason'    => null,
        ]);

        return back()->with('success', "Farmer '{$farmer->farm_name}' has been approved!");
    }

    /**
     * Reject a farmer with a reason.
     */
    public function rejectFarmer(Request $request, Farmer $farmer)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $farmer->update([
            'verification_status' => 'rejected',
            'rejection_reason'    => $request->rejection_reason,
        ]);

        return back()->with('success', "Farmer '{$farmer->farm_name}' has been rejected.");
    }
}
