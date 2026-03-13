<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Proxy Pattern: the injected repository may be the real
     * ProductRepository or the CachingProductProxy — the
     * controller doesn't know and doesn't care.
     */
    public function __construct(
        private ProductRepositoryInterface $products,
    ) {}

    public function index(): View
    {
        // Proxy Pattern: these calls may hit the DB or return cached results
        $featuredProducts = $this->products->getFeatured();
        $newProducts = $this->products->getNew();
        $saleProducts = $this->products->getSale();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->with(['children' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        // Per-category product rows
        $categoryRows = [];
        $popularCategorySlugs = ['lego', 'jucarii-din-plus', 'masini-cu-telecomanda', 'masini-electrice', 'puzzle-uri'];
        foreach ($popularCategorySlugs as $slug) {
            $cat = Category::where('slug', $slug)->first();
            if ($cat) {
                $catIds = collect([$cat->id]);
                $childIds = Category::where('parent_id', $cat->id)->pluck('id');
                $catIds = $catIds->merge($childIds);

                $products = Product::with(['primaryImage', 'activeDiscount', 'category'])
                    ->whereIn('category_id', $catIds)
                    ->where('is_active', true)
                    ->take(8)
                    ->get();

                if ($products->count() >= 2) {
                    $categoryRows[] = [
                        'category' => $cat,
                        'products' => $products,
                    ];
                }
            }
        }

        return view('frontend.home.index', compact(
            'featuredProducts',
            'newProducts',
            'saleProducts',
            'categories',
            'categoryRows',
        ));
    }
}
