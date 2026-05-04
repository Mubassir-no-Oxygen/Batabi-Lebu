<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Farmer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Buyer;
use App\Notifications\NewCropListingNotification;

class CropController extends Controller
{
    /**
     * List crops for the authenticated farmer.
     */
    public function index()
    {
        $farmer = Auth::user()->farmer;
        $crops  = $farmer->crops()->latest()->paginate(10);
        return view('farmer.crops.index', compact('crops'));
    }

    /**
     * Show create crop form.
     */
    public function create()
    {
        return view('farmer.crops.create');
    }

    /**
     * Store a new crop listing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_name'      => 'required|string|max:100',
            'category'       => 'required|in:vegetable,fruit,grain,spice,other',
            'quantity'       => 'required|numeric|min:0.1',
            'unit'           => 'required|in:kg,ton,quintal,maund',
            'price_per_unit' => 'required|numeric|min:0',
            'harvest_date'   => 'nullable|date',
            'available_from' => 'nullable|date',
            'available_until'=> 'nullable|date|after_or_equal:available_from',
            'description'    => 'nullable|string|max:1000',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:available,sold_out,upcoming',
        ]);

        $farmer = Auth::user()->farmer;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('crops', 'public');
        }

        $crop = $farmer->crops()->create($validated);

        // Notify all buyers in the same district
        if ($farmer->district) {
            $buyers = Buyer::where('district', $farmer->district)->get();
            foreach ($buyers as $b) {
                if ($b->user) {
                    $b->user->notify(new NewCropListingNotification($crop));
                }
            }
        }

        return redirect()->route('farmer.crops.index')
            ->with('success', 'Crop listing created successfully!');
    }

    /**
     * Show edit form for a crop.
     */
    public function edit(Crop $crop)
    {
        $this->authorizeCrop($crop);
        return view('farmer.crops.edit', compact('crop'));
    }

    /**
     * Update crop listing.
     */
    public function update(Request $request, Crop $crop)
    {
        $this->authorizeCrop($crop);

        $validated = $request->validate([
            'crop_name'      => 'required|string|max:100',
            'category'       => 'required|in:vegetable,fruit,grain,spice,other',
            'quantity'       => 'required|numeric|min:0.1',
            'unit'           => 'required|in:kg,ton,quintal,maund',
            'price_per_unit' => 'required|numeric|min:0',
            'harvest_date'   => 'nullable|date',
            'available_from' => 'nullable|date',
            'available_until'=> 'nullable|date|after_or_equal:available_from',
            'description'    => 'nullable|string|max:1000',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:available,sold_out,upcoming',
        ]);

        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($crop->image) Storage::disk('public')->delete($crop->image);
            $validated['image'] = $request->file('image')->store('crops', 'public');
        }

        $crop->update($validated);

        return redirect()->route('farmer.crops.index')
            ->with('success', 'Crop listing updated successfully!');
    }

    /**
     * Delete a crop listing.
     */
    public function destroy(Crop $crop)
    {
        $this->authorizeCrop($crop);

        if ($crop->image) Storage::disk('public')->delete($crop->image);
        $crop->delete();

        return redirect()->route('farmer.crops.index')
            ->with('success', 'Crop listing deleted.');
    }

    /**
     * Ensure the crop belongs to the authenticated farmer.
     */
    private function authorizeCrop(Crop $crop): void
    {
        if ($crop->farmer_id !== Auth::user()->farmer->id) {
            abort(403, 'You do not own this crop listing.');
        }
    }
}
