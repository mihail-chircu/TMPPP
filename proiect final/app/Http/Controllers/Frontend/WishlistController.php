<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['primaryImage', 'activeDiscount'])
            ->whereHas('wishlists', fn ($query) => $query->where('user_id', Auth::id()))
            ->get();

        return view('frontend.wishlist.index', compact('products'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
            ]);
            $added = true;
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'added' => $added,
            'wishlist_count' => $wishlistCount,
            'message' => $added ? 'Adaugat la favorite!' : 'Eliminat din favorite',
        ]);
    }
}
