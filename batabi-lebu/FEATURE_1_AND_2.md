# Batabi Lebu — Feature 1 & 2 Walkthrough
### Teammate Guide: Farmer Registration & Verification + Buyer Search & Filter

> **Your part:** Features 1 and 2 are already implemented and working.
> This document explains every file involved so you understand the code.

---

## ✅ Feature 1 — Farmer Registration & Verification

### What it does
- Any user can register as a Farmer by filling in personal + farm details
- After registration, farmer status is `pending` — they cannot access the dashboard yet
- Admin logs in and **approves or rejects** the farmer
- Only `approved` farmers can access farmer features

---

### Database Tables Involved

#### `users` table
Stores all accounts regardless of role.
```
id | name | email | password | role | phone | created_at
```
- `role` = `'farmer'` for farmers

#### `farmers` table
Stores farm-specific details, linked to `users`.
```
id | user_id | farm_name | district | land_size | verification_status | rejection_reason | verified_at | verified_by
```
- `verification_status` = `pending` → `approved` or `rejected`
- `verified_by` = the admin's user ID who approved/rejected

---

### Files Involved

#### Routes — `routes/web.php`
```php
// Registration & Login (public)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);

// Farmer status pages (auth required, role:farmer only)
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/pending',  [FarmerController::class, 'pending'])->name('farmer.pending');
    Route::get('/farmer/rejected', [FarmerController::class, 'rejected'])->name('farmer.rejected');
});

// Admin farmer approval routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/farmers',                    [AdminController::class, 'farmers'])->name('farmers.index');
    Route::get('/farmers/{farmer}',           [AdminController::class, 'showFarmer'])->name('farmers.show');
    Route::patch('/farmers/{farmer}/approve', [AdminController::class, 'approveFarmer'])->name('farmers.approve');
    Route::patch('/farmers/{farmer}/reject',  [AdminController::class, 'rejectFarmer'])->name('farmers.reject');
});
```

---

#### Controller 1 — `app/Http/Controllers/Auth/AuthController.php`

**Key method: `register()`**
```php
public function register(Request $request)
{
    // Validates name, email, password, role, phone
    // + farm_name, district, land_size  (if role = farmer)
    // + address, buyer_district         (if role = buyer)

    $user = User::create([...]);  // Creates users row

    if ($request->role === 'farmer') {
        Farmer::create([
            'user_id'             => $user->id,
            'farm_name'           => $request->farm_name,
            'district'            => $request->district,
            'land_size'           => $request->land_size,
            'verification_status' => 'pending',   // ← always starts pending
        ]);
    }

    Auth::login($user);
    return redirect()->route('farmer.pending');  // ← sent to pending page
}
```

**Key method: `login()`**
- After login, redirects farmer → `farmer.pending` or `farmer.rejected` (handled by middleware)
- Admin → `admin.dashboard`
- Buyer → `buyer.dashboard`

---

#### Controller 2 — `app/Http/Controllers/AdminController.php`

**`approveFarmer(Farmer $farmer)`**
```php
$farmer->update([
    'verification_status' => 'approved',
    'verified_at'         => now(),
    'verified_by'         => auth()->id(),
]);
```

**`rejectFarmer(Request $request, Farmer $farmer)`**
```php
$farmer->update([
    'verification_status' => 'rejected',
    'rejection_reason'    => $request->rejection_reason,
]);
```

---

#### Middleware — `app/Http/Middleware/`

**`RoleMiddleware.php`** — Blocks wrong roles
```php
// Usage: ->middleware('role:farmer')
// If user role doesn't match → 403 forbidden
```

**`FarmerApprovedMiddleware.php`** — Blocks unapproved farmers
```php
// If status = pending → redirect to farmer.pending page
// If status = rejected → redirect to farmer.rejected page
// If status = approved → let through
```

Both are registered in `bootstrap/app.php`:
```php
$middleware->alias([
    'role'            => RoleMiddleware::class,
    'farmer.approved' => FarmerApprovedMiddleware::class,
]);
```

---

#### Models

**`app/Models/User.php`**
```php
public function farmer() { return $this->hasOne(Farmer::class); }
public function isFarmer(): bool { return $this->role === 'farmer'; }
public function isAdmin(): bool  { return $this->role === 'admin'; }
```

**`app/Models/Farmer.php`**
```php
public function user()   { return $this->belongsTo(User::class); }
public function crops()  { return $this->hasMany(Crop::class); }
public function isApproved(): bool { return $this->verification_status === 'approved'; }
```

---

#### Blade Views

| File | Purpose |
|---|---|
| `resources/views/auth/register.blade.php` | Register form — role selector toggles farmer/buyer fields via JS |
| `resources/views/auth/login.blade.php` | Login form |
| `resources/views/farmer/pending.blade.php` | "Your account is under review" page |
| `resources/views/farmer/rejected.blade.php` | "Application rejected" page with reason |
| `resources/views/admin/dashboard.blade.php` | Admin sees pending farmers, stats |
| `resources/views/admin/farmers/index.blade.php` | Full farmer list with status filter tabs |
| `resources/views/admin/farmers/show.blade.php` | Individual farmer detail + Approve/Reject forms |

---

### Flow Diagram

```
Farmer fills register form
        ↓
User + Farmer rows created (status = pending)
        ↓
Farmer sees "Pending Approval" page
        ↓
Admin logs in → Admin Dashboard → Farmers list
        ↓
Admin clicks "Approve" or "Reject" (with reason)
        ↓
Farmer logs in again → redirected to dashboard (if approved)
                     → stays on rejected page (if rejected)
```

---

### Test Accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@batabi-lebu.com` | `password123` |
| Farmer (Approved) | `rahim@farmer.com` | `password123` |
| Farmer (Pending) | `salam@farmer.com` | `password123` |

---
---

## ✅ Feature 2 — Buyer Search & Filter Crops

### What it does
- Buyers can browse all available crop listings from verified farmers
- They can **filter** by: crop name (search), category, district, min price, max price
- Each crop card shows price, quantity, farmer name, and district
- Clicking a crop shows its full detail page

---

### Database Tables Involved

#### `crops` table
```
id | farmer_id | crop_name | category | quantity | unit |
price_per_unit | harvest_date | description | image | status
```
- Only crops with `status = 'available'` are shown to buyers
- Only crops from farmers with `verification_status = 'approved'` are shown

---

### Files Involved

#### Routes — `routes/web.php`
```php
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/crops',        [BuyerController::class, 'browseCrops'])->name('crops.index');
    Route::get('/crops/{crop}', [BuyerController::class, 'showCrop'])->name('crops.show');
});
```

---

#### Controller — `app/Http/Controllers/BuyerController.php`

**`browseCrops(Request $request)`** — The main search/filter method
```php
$query = Crop::with('farmer.user')
    ->where('status', 'available')
    ->whereHas('farmer', fn($q) => $q->where('verification_status', 'approved'));

// Filter: category
if ($request->filled('category')) {
    $query->where('category', $request->category);
}

// Filter: district (searches the farmer's district)
if ($request->filled('district')) {
    $query->whereHas('farmer', fn($q) =>
        $q->where('district', 'like', '%' . $request->district . '%')
    );
}

// Filter: price range
if ($request->filled('min_price')) {
    $query->where('price_per_unit', '>=', $request->min_price);
}
if ($request->filled('max_price')) {
    $query->where('price_per_unit', '<=', $request->max_price);
}

// Search: crop name
if ($request->filled('search')) {
    $query->where('crop_name', 'like', '%' . $request->search . '%');
}

$crops    = $query->latest()->paginate(12)->withQueryString();
$districts = Farmer::where('verification_status', 'approved')->distinct()->pluck('district');

return view('buyer.crops.index', compact('crops', 'districts'));
```

**`showCrop(Crop $crop)`** — Crop detail page
```php
$crop->load('farmer.user');
return view('buyer.crops.show', compact('crop'));
```

---

#### Model — `app/Models/Crop.php`
```php
protected $fillable = [
    'farmer_id', 'crop_name', 'category', 'quantity', 'unit',
    'price_per_unit', 'harvest_date', 'available_from', 'available_until',
    'description', 'image', 'status',
];

public function farmer() { return $this->belongsTo(Farmer::class); }
public function orders() { return $this->hasMany(Order::class); }
```

---

#### Blade Views

| File | Purpose |
|---|---|
| `resources/views/buyer/crops/index.blade.php` | Browse page with filter bar + crop card grid |
| `resources/views/buyer/crops/show.blade.php` | Crop detail page: full info + farmer profile + order form |

**Filter form (in `index.blade.php`)** — uses GET method so filters appear in URL:
```html
<form method="GET" action="{{ route('buyer.crops.index') }}">
    <input  name="search"   placeholder="e.g. Tomato">
    <select name="category"> ... </select>
    <select name="district"> ... </select>
    <input  name="min_price" type="number">
    <input  name="max_price" type="number">
    <button type="submit">Filter</button>
</form>
```

**Crop card** shows:
- Crop name + category badge
- Farmer name + district
- Price per unit (in ৳)
- Available quantity
- "View & Order" button → goes to `buyer.crops.show`

---

### Flow Diagram

```
Buyer logs in → Buyer Dashboard
        ↓
Clicks "Browse Crops"  →  /buyer/crops
        ↓
Enters filters (search, category, district, price)
        ↓
Form submits GET → BuyerController::browseCrops()
        ↓
Eloquent query filters crops from approved farmers
        ↓
Results shown as 3-column card grid (paginated, 12/page)
        ↓
Buyer clicks "View & Order" → /buyer/crops/{id}
        ↓
Full crop detail page with farmer info + order form
```

---

### Test Account

| Role | Email | Password |
|---|---|---|
| Buyer | `buyer@dhaka.com` | `password123` |

> To test filtering: Log in as buyer → Browse Crops → try filtering by "Sylhet" district or "vegetable" category.

---

## Running the App

```powershell
# Start MySQL via XAMPP Control Panel first, then:
cd d:\code\470\batabi-lebu
C:\xampp\php\php.exe artisan serve --port=8000
```

Open → **http://localhost:8000**
