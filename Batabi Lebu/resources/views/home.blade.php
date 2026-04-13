@extends('layouts.app')
@section('title', 'Home')

@section('content')

<!-- ─── Hero Section ─── -->
<section style="background: linear-gradient(135deg, #1a5c2e 0%, #2d8a4e 60%, #4caf73 100%); color: #fff; padding: 5rem 0 4rem;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-semibold">
                    BD Bangladesh's #1 Farm Direct Platform
                </span>
                <h1 class="display-4 fw-bold mb-3" style="font-family: 'Playfair Display', serif; line-height: 1.2;">
                    Fresh Crops,<br><span style="color: #ffd166;">Direct From Farmers</span>
                </h1>
                <p class="fs-5 mb-4" style="opacity: .9;">
                    Connect directly with verified farmers across Bangladesh. Get the freshest produce at fair prices — no middlemen.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow" id="hero-register-btn">
                            <i class="bi bi-person-plus me-2"></i>Get Started Free
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4" id="hero-login-btn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    @endguest
                    @auth
                        @if(auth()->user()->isBuyer())
                            <a href="{{ route('buyer.crops.index') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow">
                                <i class="bi bi-search me-2"></i>Browse Crops
                            </a>
                        @elseif(auth()->user()->isFarmer())
                            <a href="{{ route('farmer.dashboard') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Stats strip -->
                <div class="d-flex gap-4 mt-5">
                    <div>
                        <div class="fw-bold fs-4">{{ $stats['farmers'] }}+</div>
                        <div class="small" style="opacity:.8;">Verified Farmers</div>
                    </div>
                    <div style="border-left: 1px solid rgba(255,255,255,.3); padding-left: 1.5rem;">
                        <div class="fw-bold fs-4">{{ $stats['crops'] }}+</div>
                        <div class="small" style="opacity:.8;">Crops Available</div>
                    </div>
                    <div style="border-left: 1px solid rgba(255,255,255,.3); padding-left: 1.5rem;">
                        <div class="fw-bold fs-4">64</div>
                        <div class="small" style="opacity:.8;">Districts Covered</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <svg viewBox="0 0 300 260" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:340px;opacity:.92;filter:drop-shadow(0 20px 40px rgba(0,0,0,.25));">
                    <!-- Stylised wheat / crop illustration -->
                    <g fill="none" stroke="#ffd166" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Left stalk -->
                        <path d="M100 240 Q90 180 80 120" stroke-width="5"/>
                        <ellipse cx="68" cy="108" rx="18" ry="28" fill="#ffd166" opacity=".85" transform="rotate(-20 68 108)"/>
                        <ellipse cx="82" cy="80" rx="15" ry="25" fill="#ffd166" opacity=".75" transform="rotate(-10 82 80)"/>
                        <ellipse cx="72" cy="55" rx="12" ry="20" fill="#ffd166" opacity=".65" transform="rotate(5 72 55)"/>
                        <!-- Centre stalk -->
                        <path d="M150 240 Q150 170 150 100" stroke-width="6"/>
                        <ellipse cx="135" cy="90" rx="20" ry="32" fill="#ffd166" opacity=".90" transform="rotate(-15 135 90)"/>
                        <ellipse cx="150" cy="60" rx="18" ry="30" fill="#ffd166" opacity=".80" transform="rotate(0 150 60)"/>
                        <ellipse cx="165" cy="90" rx="20" ry="32" fill="#ffd166" opacity=".90" transform="rotate(15 165 90)"/>
                        <ellipse cx="150" cy="35" rx="14" ry="22" fill="#ffd166" opacity=".70"/>
                        <!-- Right stalk -->
                        <path d="M200 240 Q210 180 220 120" stroke-width="5"/>
                        <ellipse cx="232" cy="108" rx="18" ry="28" fill="#ffd166" opacity=".85" transform="rotate(20 232 108)"/>
                        <ellipse cx="218" cy="80" rx="15" ry="25" fill="#ffd166" opacity=".75" transform="rotate(10 218 80)"/>
                        <ellipse cx="228" cy="55" rx="12" ry="20" fill="#ffd166" opacity=".65" transform="rotate(-5 228 55)"/>
                        <!-- Ground line -->
                        <path d="M60 240 Q150 230 240 240" stroke-width="4" stroke="rgba(255,255,255,.3)"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- ─── How It Works ─── -->
<section class="py-5" style="background: #f8faf9;">
    <div class="container">
        <h2 class="section-title text-center mb-2">How It Works</h2>
        <p class="text-center text-muted mb-5">Simple, transparent, and fair for everyone</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--green-mid);"><i class="bi bi-person-check"></i></div>
                    <h5 class="fw-bold" style="color: var(--green-dark);">1. Farmer Registers</h5>
                    <p class="text-muted small">Farmers register with their farm details. Admin verifies and approves their account.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--green-mid);"><i class="bi bi-flower1"></i></div>
                    <h5 class="fw-bold" style="color: var(--green-dark);">2. Crops Listed</h5>
                    <p class="text-muted small">Approved farmers list their available crops with price, quantity and harvest dates.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--green-mid);"><i class="bi bi-bag-check"></i></div>
                    <h5 class="fw-bold" style="color: var(--green-dark);">3. Buyers Order</h5>
                    <p class="text-muted small">Buyers browse, search, and place bulk orders directly with farmers — no middlemen.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Featured Crops ─── -->
@if($featuredCrops->count())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Fresh Crops Available</h2>
            @auth
                @if(auth()->user()->isBuyer())
                    <a href="{{ route('buyer.crops.index') }}" class="btn btn-outline-success btn-sm">View All <i class="bi bi-arrow-right"></i></a>
                @endif
            @endauth
        </div>
        <div class="row g-4">
            @foreach($featuredCrops as $crop)
            <div class="col-md-4 col-lg-2-custom">
                <div class="card crop-card h-100">
                    @if($crop->image)
                        <img src="{{ Storage::url($crop->image) }}" alt="{{ $crop->crop_name }}" class="card-img-top">
                    @else
                        <div class="crop-placeholder">{{ ['🌽','🍅','🥬','🧅','🌾','🍆'][array_rand(['🌽','🍅','🥬','🧅','🌾','🍆'])] }}</div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold mb-0">{{ $crop->crop_name }}</h6>
                            <span class="badge bg-success-subtle text-success">{{ ucfirst($crop->category) }}</span>
                        </div>
                        <p class="small text-muted mb-1">
                            <i class="bi bi-geo-alt"></i> {{ $crop->farmer->district }}
                        </p>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-person"></i> {{ $crop->farmer->user->name }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold" style="color: var(--green-dark);">
                                ৳{{ number_format($crop->price_per_unit, 0) }}/{{ $crop->unit }}
                            </span>
                            <span class="small text-muted">{{ $crop->quantity }} {{ $crop->unit }} avail.</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ─── CTA Section ─── -->
<section style="background: linear-gradient(135deg, #e8890a, #f5a623); padding: 4rem 0; color: #fff;">
    <div class="container text-center">
        <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Ready to Join Batabi Lebu?</h2>
        <p class="fs-5 mb-4" style="opacity: .9;">Whether you're a farmer looking to sell or a buyer seeking fresh produce — we connect you directly.</p>
        @guest
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-dark btn-lg px-4 fw-bold" id="cta-farmer-btn">
                <i class="bi bi-person-plus me-2"></i>I'm a Farmer
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg px-4 fw-bold" id="cta-buyer-btn">
                <i class="bi bi-search me-2"></i>I'm a Buyer
            </a>
        </div>
        @endguest
    </div>
</section>

@endsection
