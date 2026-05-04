<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Show login form ──────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Handle login ─────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    // ─── Show registration form ───────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ─── Handle registration ──────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:farmer,buyer',
            'phone'    => 'required|string|max:20',

            // Farmer-specific fields
            'farm_name'  => 'nullable|required_if:role,farmer|string|max:150',
            'district'   => 'nullable|required_if:role,farmer|string|max:100',
            'land_size'  => 'nullable|numeric|min:0',

            // Buyer-specific fields
            'address'    => 'nullable|string|max:255',
            'buyer_district' => 'nullable|string|max:100',
        ]);

        // Create base user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
        ]);

        // Create role-specific profile
        if ($request->role === 'farmer') {
            Farmer::create([
                'user_id'             => $user->id,
                'farm_name'           => $request->farm_name,
                'district'            => $request->district,
                'land_size'           => $request->land_size,
                'verification_status' => 'pending',
            ]);
        } elseif ($request->role === 'buyer') {
            Buyer::create([
                'user_id'  => $user->id,
                'address'  => $request->address,
                'district' => $request->buyer_district,
            ]);
        }

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    // ─── Logout ───────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    // ─── Redirect based on role ───────────────────────────────
    private function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'farmer'  => redirect()->route('farmer.dashboard'),
            'buyer'   => redirect()->route('buyer.dashboard'),
            default   => redirect()->route('home'),
        };
    }
}
