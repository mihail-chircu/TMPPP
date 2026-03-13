<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $product = Product::with(['images', 'category', 'activeDiscount'])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views_count');

        $relatedProducts = Product::with(['primaryImage', 'activeDiscount'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $recentlyViewedIds = $request->session()->get('recently_viewed', []);

        $recentlyViewedIds = array_filter($recentlyViewedIds, fn ($id) => $id !== $product->id);
        array_unshift($recentlyViewedIds, $product->id);
        $recentlyViewedIds = array_slice($recentlyViewedIds, 0, 10);

        $request->session()->put('recently_viewed', $recentlyViewedIds);

        return view('frontend.product.show', compact(
            'product',
            'relatedProducts',
            'recentlyViewedIds',
        ));
    }
}
