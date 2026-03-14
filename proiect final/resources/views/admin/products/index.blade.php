@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn-primary inline-flex items-center rounded-lg bg-kinder-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="admin-card rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name, SKU, or brand..." class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                </div>
                <button type="submit" class="btn-secondary rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Search
                </button>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 font-medium text-gray-500">Image</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Category</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Price</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Stock</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($product->primary_image_url)
                                    <img src="{{ asset('storage/' . $product->primary_image_url) }}" alt="{{ $product->name }}" class="h-[50px] w-[50px] rounded-lg object-cover">
                                @else
                                    <img src="{{ asset('images/placeholder-robot.svg') }}" alt="Fara imagine" class="h-[50px] w-[50px] rounded-lg object-contain bg-gray-50 p-1">
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                @if($product->sku)
                                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                @endif
                                @if($product->badge)
                                    @if($product->badge === 'new')
                                        <span class="badge-new mt-1 inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">NEW</span>
                                    @elseif($product->badge === 'hot')
                                        <span class="badge-hot mt-1 inline-flex rounded-full bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800">HOT</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($product->activeDiscount)
                                    <div class="text-gray-400 line-through">{{ number_format($product->price, 2) }} MDL</div>
                                    <div class="badge-sale font-medium text-red-600">{{ number_format($product->activeDiscount->discounted_price, 2) }} MDL</div>
                                @else
                                    <div class="font-medium text-gray-900">{{ number_format($product->price, 2) }} MDL</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->stock <= 0)
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Out of Stock</span>
                                @elseif($product->stock < 5)
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">{{ $product->stock }}</span>
                                @else
                                    <span class="text-gray-700">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-kinder-600" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    {{-- Prototype Pattern: Duplicate product --}}
                                    <form action="{{ route('admin.products.duplicate', $product) }}" method="POST" onsubmit="return confirm('Duplicate this product?');">
                                        @csrf
                                        <button type="submit" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-green-600" title="Duplicate">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-red-600" title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-medium">No products found.</p>
                                <p class="mt-1 text-sm">Get started by adding your first product.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
