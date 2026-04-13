<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = Auth::user()->role;
            if ($role === 'farmer') {
                return redirect()->route('farmer.dashboard');
            } elseif ($role === 'buyer') {
                return redirect()->route('buyer.dashboard');
            }
            
            return redirect('/');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:farmer,buyer',
        ]);

        // Specific validation branches
        if ($request->role === 'farmer') {
            $request->validate([
                'farm_name' => 'required|string|max:255',
                'district' => 'required|string|max:255',
            ]);
        } elseif ($request->role === 'buyer') {
            $request->validate([
                'company_name' => 'required|string|max:255',
            ]);
        }

        // 1. Create Core User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Stored securely
            'role' => $request->role,
        ]);

        // 2. Map Profile based on constraints
        if ($user->role === 'farmer') {
            Farmer::create([
                'user_id' => $user->id,
                'farm_name' => $request->farm_name,
                'district' => $request->district,
                'verification_status' => 'pending',
            ]);
        } elseif ($user->role === 'buyer') {
            Buyer::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                // optional fields left empty defaults
            ]);
        }

        // Auto-login after registration
        Auth::login($user);

        // Redirect appropriately
        if ($user->role === 'farmer') {
            return redirect()->route('farmer.dashboard')->with('success', 'Account created! Welcome to Batabi Lebu.');
        } else {
            return redirect()->route('buyer.dashboard')->with('success', 'Buyer profile established.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
