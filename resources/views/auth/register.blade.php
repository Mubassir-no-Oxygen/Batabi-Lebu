@extends('layouts.app')

@section('title', 'Create Account - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: center; padding: 2rem 0;">
    <div class="glass-card" style="width: 100%; max-width: 600px; padding: 3rem;">
        
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <i class='bx bx-user-plus' style="font-size: 3.5rem; color: var(--primary);"></i>
            <h1 style="font-size: 2rem; margin-top: 10px;">Join Batabi Lebu</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Empowering direct agricultural trade in Bangladesh.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <ul style="margin-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf
            
            <!-- Standard User Fields -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group" style="grid-column: 1 / -1; margin-bottom:0;">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Rahim Uddin">
                </div>

                <div class="form-group" style="grid-column: 1 / -1; margin-bottom:0;">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="rahim@example.com">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Min. 8 characters">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Retype password">
                </div>
            </div>

            <hr style="border: 0; border-top: 1px dashed var(--border); margin: 2rem 0;">

            <!-- Role Selection -->
            <label class="form-label" style="text-align: center; font-size: 1.2rem; margin-bottom: 1.5rem;">I want to register as a:</label>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <label class="role-card" id="farmerCard">
                    <input type="radio" name="role" value="farmer" style="display: none;" onchange="toggleRoleFields('farmer')" required>
                    <div class="role-content glass-card" style="text-align: center; padding: 1.5rem; cursor: pointer; border: 2px solid transparent; transition: var(--transition);">
                        <i class='bx bx-landscape' style="font-size: 3rem; color: var(--primary); margin-bottom: 10px;"></i>
                        <h3 style="margin-bottom: 5px;">Farmer</h3>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">I want to sell my crops directly.</p>
                    </div>
                </label>

                <label class="role-card" id="buyerCard">
                    <input type="radio" name="role" value="buyer" style="display: none;" onchange="toggleRoleFields('buyer')">
                    <div class="role-content glass-card" style="text-align: center; padding: 1.5rem; cursor: pointer; border: 2px solid transparent; transition: var(--transition);">
                        <i class='bx bx-cart' style="font-size: 3rem; color: var(--accent); margin-bottom: 10px;"></i>
                        <h3 style="margin-bottom: 5px;">Buyer</h3>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">I want to buy agricultural supplies.</p>
                    </div>
                </label>
            </div>

            <!-- Dynamic Profile Fields -->
            <div id="dynamicFields" style="display: none; background: var(--surface-hover); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 2rem; animation: slideIn 0.3s ease-out;">
                
                <!-- Farmer Fields -->
                <div id="farmerFields" style="display: none;">
                    <h4 style="margin-bottom: 1rem; color: var(--primary);"><i class='bx bx-info-circle'></i> Farmer Details Required</h4>
                    <div class="form-group">
                        <label class="form-label">Farm Name</label>
                        <input type="text" name="farm_name" id="farmName" class="form-control" placeholder="e.g. Rahim Green Farm">
                    </div>
                    <div class="form-group">
                        <label class="form-label">District Location</label>
                        <input type="text" name="district" id="farmerDistrict" class="form-control" placeholder="e.g. Sylhet">
                    </div>
                </div>

                <!-- Buyer Fields -->
                <div id="buyerFields" style="display: none;">
                    <h4 style="margin-bottom: 1rem; color: var(--accent);"><i class='bx bx-info-circle'></i> Buyer Context</h4>
                    <div class="form-group">
                        <label class="form-label">Company / Organization Name</label>
                        <input type="text" name="company_name" id="companyName" class="form-control" placeholder="e.g. Dhaka Traders Ltd.">
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                Create Account
            </button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.95rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Sign in here</a>
        </p>

    </div>
</div>

<style>
    /* Styling for the radio cards */
    input[type="radio"]:checked + .role-content {
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        background: rgba(16, 185, 129, 0.05);
    }
    input[value="buyer"]:checked + .role-content {
        border-color: var(--accent);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
        background: rgba(245, 158, 11, 0.05);
    }
    .role-content:hover {
        transform: translateY(-5px);
    }
</style>

<script>
    function toggleRoleFields(role) {
        const dynamicContainer = document.getElementById('dynamicFields');
        const farmerFields = document.getElementById('farmerFields');
        const buyerFields = document.getElementById('buyerFields');

        // Reset display and required attributes
        dynamicContainer.style.display = 'block';
        farmerFields.style.display = 'none';
        buyerFields.style.display = 'none';

        document.getElementById('farmName').removeAttribute('required');
        document.getElementById('farmerDistrict').removeAttribute('required');
        document.getElementById('companyName').removeAttribute('required');

        if (role === 'farmer') {
            farmerFields.style.display = 'block';
            document.getElementById('farmName').setAttribute('required', 'true');
            document.getElementById('farmerDistrict').setAttribute('required', 'true');
        } else if (role === 'buyer') {
            buyerFields.style.display = 'block';
            document.getElementById('companyName').setAttribute('required', 'true');
        }
    }

    // Trigger on load if old value exists (from validation redirection)
    document.addEventListener("DOMContentLoaded", function() {
        const checkedRadio = document.querySelector('input[name="role"]:checked');
        if (checkedRadio) {
            toggleRoleFields(checkedRadio.value);
        }
    });
</script>
@endsection
