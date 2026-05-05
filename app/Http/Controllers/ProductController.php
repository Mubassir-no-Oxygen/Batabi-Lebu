<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Farmer: browse all active products
    public function index(Request $request)
    {
        $query = Product::with('supplier')
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc')       $query->orderBy('price', 'asc');
        elseif ($sort === 'price_desc')  $query->orderBy('price', 'desc');
        else                             $query->latest();

        $products   = $query->paginate(12);
        $categories = ['seed', 'fertilizer', 'tool'];

        return view('store.index', compact('products', 'categories'));
    }

    // Farmer: view single product
    public function show($id)
    {
        $product = Product::with('supplier')
            ->where('status', 'active')
            ->findOrFail($id);

        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('store.show', compact('product', 'related'));
    }

    // Supplier: list own products
    public function supplierProducts()
    {
        $products = Product::where('supplier_id', Auth::id())
            ->latest()
            ->get();

        return view('supplier.products.index', compact('products'));
    }

    // Supplier: create form
    public function create()
    {
        return view('supplier.products.create');
    }

    // Supplier: save new product
    public function store(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'category'       => 'required|in:seed,fertilizer,tool',
            'description'    => 'required|string|max:2000',
            'price'          => 'required|numeric|min:1',
            'unit'           => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'supplier_id'    => Auth::id(),
            'product_name'   => $request->product_name,
            'category'       => $request->category,
            'description'    => $request->description,
            'price'          => $request->price,
            'unit'           => $request->unit,
            'stock_quantity' => $request->stock_quantity,
            'image'          => $imagePath,
            'status'         => 'active',
        ]);

        return redirect()->route('supplier.products.index')
            ->with('success', 'Product listed successfully!');
    }

    // Supplier: edit form
    public function edit($id)
    {
        $product = Product::where('supplier_id', Auth::id())->findOrFail($id);
        return view('supplier.products.edit', compact('product'));
    }

    // Supplier: update product
    public function update(Request $request, $id)
    {
        $product = Product::where('supplier_id', Auth::id())->findOrFail($id);

        $request->validate([
            'product_name'   => 'required|string|max:255',
            'category'       => 'required|in:seed,fertilizer,tool',
            'description'    => 'required|string|max:2000',
            'price'          => 'required|numeric|min:1',
            'unit'           => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'product_name'   => $request->product_name,
            'category'       => $request->category,
            'description'    => $request->description,
            'price'          => $request->price,
            'unit'           => $request->unit,
            'stock_quantity' => $request->stock_quantity,
            'status'         => $request->status,
        ]);

        return redirect()->route('supplier.products.index')
            ->with('success', 'Product updated successfully!');
    }
}
