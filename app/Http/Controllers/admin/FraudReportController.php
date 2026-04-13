<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudReport;
use Illuminate\Http\Request;

class FraudReportController extends Controller
{
    public function index()
    {
        $reports = FraudReport::with(['reporter', 'reportedUser', 'order'])
                              ->latest()
                              ->get();
        return view('admin.fraud_reports.index', compact('reports'));
    }

    public function update(Request $request, FraudReport $report)
    {
        $request->validate([
            'status'     => 'required|in:investigating,resolved,dismissed',
            'admin_note' => 'nullable|string',
        ]);

        $report->update([
            'status'      => $request->status,
            'admin_note'  => $request->admin_note,
            'resolved_at' => in_array($request->status, ['resolved', 'dismissed']) ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Report updated successfully.');
    }
}