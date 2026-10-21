<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        $products = Product::with(['creator', 'updater'])->latest('id')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:1000'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        $imagePath = $validated['image'] ?? null;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $imagePath;
        }

        Product::create([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'image' => $imagePath,
            'category' => $validated['category'] ?? null,
            'size' => $validated['size'] ?? null,
            'price' => $validated['price'] ?? null,
            'selling_price' => $validated['selling_price'],
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): View
    {
        $product = Product::with(['creator', 'updater', 'orderDetails.order'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:1000'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        $imagePath = $validated['image'] ?? $product->image;
        if ($request->hasFile('image_file')) {
            $uploaded = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $uploaded;
        }

        $product->update([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'image' => $imagePath,
            'category' => $validated['category'] ?? null,
            'size' => $validated['size'] ?? null,
            'price' => $validated['price'] ?? null,
            'selling_price' => $validated['selling_price'],
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
