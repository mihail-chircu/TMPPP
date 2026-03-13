<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        $discounts = Discount::with('product')
            ->latest()
            ->paginate(15);

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.discounts.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'discount_percent' => ['required', 'numeric', 'min:1', 'max:99'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $originalPrice = $product->price;
        $discountedPrice = round($originalPrice * (1 - $validated['discount_percent'] / 100), 2);

        Discount::create([
            'product_id' => $validated['product_id'],
            'discount_percent' => $validated['discount_percent'],
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', __('Discount created successfully.'));
    }

    public function edit(Discount $discount): View
    {
        $discount->load('product');

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'discount_percent' => ['required', 'numeric', 'min:1', 'max:99'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $originalPrice = $product->price;
        $discountedPrice = round($originalPrice * (1 - $validated['discount_percent'] / 100), 2);

        $discount->update([
            'product_id' => $validated['product_id'],
            'discount_percent' => $validated['discount_percent'],
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', __('Discount updated successfully.'));
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', __('Discount deleted successfully.'));
    }
}
