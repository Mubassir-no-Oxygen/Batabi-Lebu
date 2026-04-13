<?php

namespace App\Http\Controllers;

use App\Models\FraudReport;
use App\Models\User;
use Illuminate\Http\Request;

class FraudReportController extends Controller
{
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('fraud_reports.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'type'             => 'required|in:fake_listing,payment_fraud,non_delivery,quality_fraud,impersonation,scam,other',
            'description'      => 'required|string|min:20',
            'order_id'         => 'nullable|exists:orders,id',
            'evidence'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidence', 'public');
        }

        FraudReport::create([
            'reporter_id'      => auth()->id(),
            'reported_user_id' => $request->reported_user_id,
            'order_id'         => $request->order_id,
            'type'             => $request->type,
            'description'      => $request->description,
            'evidence_path'    => $evidencePath,
            'status'           => 'pending',
        ]);

        return redirect()->route('fraud.my-reports')
                         ->with('success', 'Report submitted. Admin will review shortly.');
    }

    public function myReports()
    {
        $reports = FraudReport::where('reporter_id', auth()->id())
                              ->latest()
                              ->get();
        return view('fraud_reports.my_reports', compact('reports'));
    }
}