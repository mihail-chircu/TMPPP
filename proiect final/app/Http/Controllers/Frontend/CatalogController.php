<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Catalog\ProductSorter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private ProductSorter $sorter,
    ) {}

    public function index(Request $request): View
    {
        $products = $this->getFilteredProducts($request);

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $brands = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        $priceRange = [
            'min' => Product::where('is_active', true)->min('price') ?? 0,
            'max' => Product::where('is_active', true)->max('price') ?? 0,
        ];

        return view('frontend.catalog.index', compact(
            'products',
            'categories',
            'brands',
            'priceRange',
        ));
    }

    public function filter(Request $request): JsonResponse
    {
        $products = $this->getFilteredProducts($request);

        return response()->json([
            'html' => view('frontend.catalog._product-grid', compact('products'))->render(),
            'pagination' => $products->links()->render(),
        ]);
    }

    protected function getFilteredProducts(Request $request)
    {
        $query = Product::with(['primaryImage', 'activeDiscount', 'category'])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $categories = (array) $request->input('category');
            $query->whereHas('category', fn (Builder $q) => $q->whereIn('slug', $categories));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('age')) {
            $age = (int) $request->input('age');
            $query->where('age_min', '<=', $age)->where('age_max', '>=', $age);
        }

        if ($request->filled('brand')) {
            $brands = (array) $request->input('brand');
            $query->whereIn('brand', $brands);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('on_sale')) {
            $query->onSale();
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Strategy Pattern: delegate sorting to the selected strategy
        $query = $this->sorter->apply($query, $request->input('sort'));

        return $query->paginate(12)->withQueryString();
    }
}
