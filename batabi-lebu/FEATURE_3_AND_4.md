# Batabi Lebu — Feature 3 & 4 Walkthrough
### Teammate Guide: Farmer Crop Listing + Bulk Order Request System

> **Your part:** Features 3 and 4 are already implemented and working.
> This document explains every file involved so you understand the code.

---

## ✅ Feature 3 — Farmer Crop Listing (CRUD)

### What it does
- Only **approved** farmers can access crop management
- Farmers can **add**, **edit**, and **delete** their crop listings
- Each listing includes: name, category, quantity, unit, price, dates, description, and an optional photo
- Farmers can mark crops as `available`, `upcoming`, or `sold_out`

---

### Database Table — `crops`

```
id | farmer_id | crop_name | category | quantity | unit |
price_per_unit | harvest_date | available_from | available_until |
description | image | status | created_at | updated_at
```

| Column | Type | Values |
|---|---|---|
| `category` | ENUM | `vegetable`, `fruit`, `grain`, `spice`, `other` |
| `unit` | ENUM | `kg`, `ton`, `quintal`, `maund` |
| `status` | ENUM | `available`, `sold_out`, `upcoming` |
| `image` | VARCHAR | Path stored in `storage/public/crops/` |

---

### Files Involved

#### Routes — `routes/web.php`
```php
// All routes below require: logged in + role:farmer + verification_status = approved
Route::middleware(['auth', 'role:farmer', 'farmer.approved'])
     ->prefix('farmer')
     ->name('farmer.')
     ->group(function () {

    Route::get('/dashboard',           [FarmerController::class, 'dashboard'])->name('dashboard');

    // Crop CRUD
    Route::get('/crops',              [CropController::class, 'index'])->name('crops.index');
    Route::get('/crops/create',       [CropController::class, 'create'])->name('crops.create');
    Route::post('/crops',             [CropController::class, 'store'])->name('crops.store');
    Route::get('/crops/{crop}/edit',  [CropController::class, 'edit'])->name('crops.edit');
    Route::put('/crops/{crop}',       [CropController::class, 'update'])->name('crops.update');
    Route::delete('/crops/{crop}',    [CropController::class, 'destroy'])->name('crops.destroy');
});
```

---

#### Controller — `app/Http/Controllers/CropController.php`

**`index()`** — List farmer's own crops
```php
$farmer = Auth::user()->farmer;
$crops  = $farmer->crops()->latest()->paginate(10);
return view('farmer.crops.index', compact('crops'));
```

**`store(Request $request)`** — Create new crop
```php
// Validation rules:
'crop_name'      => 'required|string|max:100',
'category'       => 'required|in:vegetable,fruit,grain,spice,other',
'quantity'       => 'required|numeric|min:0.1',
'unit'           => 'required|in:kg,ton,quintal,maund',
'price_per_unit' => 'required|numeric|min:0',
'harvest_date'   => 'nullable|date',
'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
'status'         => 'required|in:available,sold_out,upcoming',

// Image upload (if provided):
$validated['image'] = $request->file('image')->store('crops', 'public');
// → saves to storage/app/public/crops/filename.jpg
// → accessible at /storage/crops/filename.jpg via symlink

$farmer->crops()->create($validated);  // Links to logged-in farmer automatically
```

**`update(Request $request, Crop $crop)`** — Same rules as store
```php
// Ownership check runs first:
$this->authorizeCrop($crop);  // Aborts 403 if crop belongs to another farmer

// If new image uploaded → delete old one first:
if ($request->hasFile('image')) {
    if ($crop->image) Storage::disk('public')->delete($crop->image);
    $validated['image'] = $request->file('image')->store('crops', 'public');
}

$crop->update($validated);
```

**`destroy(Crop $crop)`** — Delete crop + its image
```php
$this->authorizeCrop($crop);
if ($crop->image) Storage::disk('public')->delete($crop->image);
$crop->delete();
```

**`authorizeCrop(Crop $crop)`** — Ownership guard
```php
// Prevents a farmer from editing/deleting another farmer's crop
if ($crop->farmer_id !== Auth::user()->farmer->id) {
    abort(403, 'You do not own this crop listing.');
}
```

---

#### Controller — `app/Http/Controllers/FarmerController.php`

**`dashboard()`** — Stats overview for farmer home
```php
$stats = [
    'total_crops'    => $farmer->crops()->count(),
    'active_crops'   => $farmer->crops()->where('status', 'available')->count(),
    'pending_orders' => Order::whereHas('crop', ...)->where('status', 'pending')->count(),
    'accepted_orders'=> Order::whereHas('crop', ...)->where('status', 'accepted')->count(),
];
$recentOrders = Order::whereHas('crop', ...)->with('crop', 'buyer.user')->latest()->take(5)->get();
return view('farmer.dashboard', compact('recentOrders', 'stats'));
```

---

#### Model — `app/Models/Crop.php`
```php
protected $fillable = [
    'farmer_id', 'crop_name', 'category', 'quantity', 'unit',
    'price_per_unit', 'harvest_date', 'available_from', 'available_until',
    'description', 'image', 'status',
];

protected $casts = [
    'harvest_date'    => 'date',
    'available_from'  => 'date',
    'available_until' => 'date',
];

public function farmer() { return $this->belongsTo(Farmer::class); }
public function orders() { return $this->hasMany(Order::class); }
```

---

#### Middleware — `farmer.approved`

Located at `app/Http/Middleware/FarmerApprovedMiddleware.php`

```php
// All routes inside the 'farmer.approved' group check this:
// → pending  → redirect to /farmer/pending
// → rejected → redirect to /farmer/rejected
// → approved → proceed normally
```

---

#### Blade Views

| File | Purpose |
|---|---|
| `resources/views/farmer/dashboard.blade.php` | 4 stat cards + recent orders table |
| `resources/views/farmer/crops/index.blade.php` | Table of all farmer's crops with edit/delete buttons |
| `resources/views/farmer/crops/create.blade.php` | Create crop form (all fields + image upload) |
| `resources/views/farmer/crops/edit.blade.php` | Edit form — pre-filled with existing data + current image preview |

**In `index.blade.php`** — Delete uses a POST form with `@method('DELETE')`:
```html
<form action="{{ route('farmer.crops.destroy', $crop) }}" method="POST"
      onsubmit="return confirm('Delete this crop listing?')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-trash"></i>
    </button>
</form>
```

**In `create.blade.php`** — Form must use `enctype` for image upload:
```html
<form action="{{ route('farmer.crops.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    ...
</form>
```

---

### Flow Diagram

```
Farmer logs in (approved) → Farmer Dashboard
        ↓
Clicks "Add Crop" → /farmer/crops/create
        ↓
Fills form → POST /farmer/crops → CropController::store()
        ↓
Validates → saves image → creates crops row
        ↓
Redirect to /farmer/crops → crop appears in list
        ↓
Farmer can Edit (GET /farmer/crops/{id}/edit → PUT)
              or Delete (DELETE /farmer/crops/{id})
```

---

### Test Account

| Role | Email | Password |
|---|---|---|
| Farmer (approved) | `rahim@farmer.com` | `password123` |

> After login: go to **My Crops** → Add a new crop, then edit and delete it.

---
---

## ✅ Feature 4 — Bulk Order Request System

### What it does
- **Buyer** submits an order for a crop: specifies quantity + optional price offer + note
- Duplicate pending orders for the same crop by the same buyer are prevented
- **Farmer** sees all incoming orders and can **Accept** or **Reject** each one
- On accept: `final_price` is set (uses offered price if provided, else listing price)
- Both sides can view their **order history**

---

### Database Table — `orders`

```
id | buyer_id | crop_id | requested_quantity | offered_price |
final_price | bulk_discount_percent | note | admin_note |
status | accepted_at | completed_at | created_at | updated_at
```

| Column | Notes |
|---|---|
| `offered_price` | Optional — buyer's counter-price |
| `final_price` | Set when farmer accepts |
| `bulk_discount_percent` | Optional discount for large orders (future use) |
| `status` | `pending` → `accepted` or `rejected` → `completed` or `cancelled` |

---

### Files Involved

#### Routes — `routes/web.php`

**Buyer routes:**
```php
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/crops/{crop}',            [BuyerController::class, 'showCrop'])->name('crops.show');
    Route::post('/crops/{crop}/order',     [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders',                  [OrderController::class, 'buyerOrders'])->name('orders.index');
});
```

**Farmer routes:**
```php
Route::middleware(['auth', 'role:farmer', 'farmer.approved'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/orders',                          [OrderController::class, 'farmerOrders'])->name('orders.index');
    Route::patch('/orders/{order}/accept',         [OrderController::class, 'accept'])->name('orders.accept');
    Route::patch('/orders/{order}/reject',         [OrderController::class, 'reject'])->name('orders.reject');
});
```

---

#### Controller — `app/Http/Controllers/OrderController.php`

**`store(Request $request, Crop $crop)`** — Buyer places an order
```php
// Validation:
'requested_quantity' => 'required|numeric|min:0.1|max:' . $crop->quantity,
'offered_price'      => 'nullable|numeric|min:0',
'note'               => 'nullable|string|max:500',

// Prevent duplicate pending orders:
$existing = Order::where('buyer_id', $buyer->id)
    ->where('crop_id', $crop->id)
    ->where('status', 'pending')
    ->first();

if ($existing) {
    return back()->with('error', 'You already have a pending order for this crop.');
}

Order::create([
    'buyer_id'           => $buyer->id,
    'crop_id'            => $crop->id,
    'requested_quantity' => $validated['requested_quantity'],
    'offered_price'      => $validated['offered_price'] ?? null,
    'note'               => $validated['note'] ?? null,
    'status'             => 'pending',
]);
```

**`farmerOrders()`** — Farmer sees incoming orders
```php
// Only shows orders for THIS farmer's crops:
$orders = Order::whereHas('crop', fn($q) => $q->where('farmer_id', $farmer->id))
    ->with('crop', 'buyer.user')
    ->latest()
    ->paginate(10);
```

**`accept(Order $order)`** — Farmer accepts
```php
$this->authorizeOrder($order);  // Ownership check

$order->update([
    'status'      => 'accepted',
    'final_price' => $order->offered_price ?? $order->crop->price_per_unit,
    'accepted_at' => now(),
]);
```

**`reject(Order $order)`** — Farmer rejects
```php
$this->authorizeOrder($order);
$order->update(['status' => 'rejected']);
```

**`authorizeOrder(Order $order)`** — Ownership guard
```php
// Farmer can only accept/reject orders for their OWN crops:
if ($order->crop->farmer_id !== Auth::user()->farmer->id) {
    abort(403, 'You do not own this order.');
}
```

**`buyerOrders()`** — Buyer's order history
```php
$buyer  = Auth::user()->buyer;
$orders = $buyer->orders()->with('crop.farmer.user')->latest()->paginate(10);
return view('buyer.orders.index', compact('orders'));
```

---

#### Models

**`app/Models/Order.php`**
```php
protected $fillable = [
    'buyer_id', 'crop_id', 'requested_quantity',
    'offered_price', 'final_price', 'bulk_discount_percent',
    'note', 'admin_note', 'status', 'accepted_at', 'completed_at',
];

public function buyer()  { return $this->belongsTo(Buyer::class); }
public function crop()   { return $this->belongsTo(Crop::class); }

// Future relationships (stubs — don't touch):
public function negotiations() { return $this->hasMany(Negotiation::class); }
public function payment()      { return $this->hasOne(Payment::class); }
public function delivery()     { return $this->hasOne(Delivery::class); }
public function review()       { return $this->hasOne(Review::class); }
public function agreement()    { return $this->hasOne(Agreement::class); }
public function fraudReports() { return $this->hasMany(FraudReport::class); }
```

**`app/Models/Buyer.php`**
```php
public function user()   { return $this->belongsTo(User::class); }
public function orders() { return $this->hasMany(Order::class); }
```

---

#### Blade Views

| File | Purpose |
|---|---|
| `resources/views/buyer/crops/show.blade.php` | Crop detail + order submission form |
| `resources/views/buyer/orders/index.blade.php` | Buyer's order history table |
| `resources/views/farmer/orders/index.blade.php` | Farmer's incoming orders table + Accept/Reject buttons |

**Order form (in `buyer/crops/show.blade.php`):**
```html
<form action="{{ route('buyer.orders.store', $crop) }}" method="POST">
    @csrf
    <input type="number" name="requested_quantity" max="{{ $crop->quantity }}" required>
    <input type="number" name="offered_price">   <!-- optional -->
    <textarea name="note"></textarea>            <!-- optional -->
    <button type="submit">Submit Order Request</button>
</form>
```

The view also has a **live price estimate** (JavaScript):
```js
// When buyer types quantity or price, it auto-calculates estimated total
const total = qty * (offeredPrice || askingPrice);
document.getElementById('price-estimate').textContent = '৳' + total.toLocaleString();
```

**Accept/Reject buttons (in `farmer/orders/index.blade.php`):**
```html
<!-- Accept -->
<form action="{{ route('farmer.orders.accept', $order) }}" method="POST">
    @csrf @method('PATCH')
    <button type="submit" class="btn btn-sm btn-success"
            onclick="return confirm('Accept this order?')">✓</button>
</form>

<!-- Reject -->
<form action="{{ route('farmer.orders.reject', $order) }}" method="POST">
    @csrf @method('PATCH')
    <button type="submit" class="btn btn-sm btn-outline-danger"
            onclick="return confirm('Reject this order?')">✗</button>
</form>
```

> ⚠️ Only show Accept/Reject if `$order->status === 'pending'` — already handled in the view.

---

### Flow Diagram

```
Buyer browses crops → clicks "View & Order" → crop detail page
        ↓
Buyer enters: quantity (required) + offered price (optional) + note
        ↓
POST /buyer/crops/{crop}/order → OrderController::store()
        ↓
Validates → duplicate check → creates order (status = pending)
        ↓
Buyer redirected to /buyer/orders → sees "Pending" status
        ↓
        ↓ (Meanwhile on farmer side)
        ↓
Farmer logs in → /farmer/orders → sees the request
        ↓
Farmer clicks Accept → PATCH /farmer/orders/{id}/accept
        ↓
Order updated: status = accepted, final_price set, accepted_at = now()
        ↓
        OR
        ↓
Farmer clicks Reject → PATCH /farmer/orders/{id}/reject
        ↓
Order updated: status = rejected
        ↓
Both sides can see full history in their order pages
```

---

### Status Badge Reference

These CSS classes are already defined in the master layout:

| Status | Badge Class | Color |
|---|---|---|
| `pending` | `badge-pending` | Yellow |
| `accepted` | `badge-accepted` | Blue |
| `rejected` | `badge-rejected` | Red |
| `completed` | `badge-completed` | Purple |
| `approved` | `badge-approved` | Green |

Usage in Blade:
```html
<span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
```

---

### Test Accounts

| Role | Email | Password |
|---|---|---|
| Buyer | `buyer@dhaka.com` | `password123` |
| Farmer (approved) | `rahim@farmer.com` | `password123` |

**Testing flow:**
1. Login as **buyer** → Browse Crops → click "View & Order" on any crop
2. Enter a quantity → submit order
3. Logout → Login as **farmer** → go to Orders
4. Click Accept or Reject
5. Logout → Login as **buyer** again → check My Orders — status updated

---

## Running the App

```powershell
# Start MySQL via XAMPP Control Panel first, then:
cd d:\code\470\batabi-lebu
C:\xampp\php\php.exe artisan serve --port=8000
```

Open → **http://localhost:8000**
