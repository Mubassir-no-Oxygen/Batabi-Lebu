<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FarmerApprovedMiddleware — ensures farmer's verification_status is 'approved'.
 * Apply after auth + role:farmer middleware.
 */
class FarmerApprovedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role === 'farmer') {
            $farmer = $user->farmer;

            if (!$farmer) {
                abort(403, 'Farmer profile not found.');
            }

            if ($farmer->verification_status === 'pending') {
                return redirect()->route('farmer.pending')
                    ->with('info', 'Your account is awaiting admin approval.');
            }

            if ($farmer->verification_status === 'rejected') {
                return redirect()->route('farmer.rejected')
                    ->with('error', 'Your farmer application was rejected. Reason: ' . $farmer->rejection_reason);
            }
        }

        return $next($request);
    }
}
