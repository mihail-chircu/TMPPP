@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Discount</h1>
            <p class="mt-1 text-sm text-gray-500">Editing discount for: {{ $discount->product?->name ?? 'Unknown Product' }}</p>
        </div>
        <a href="{{ route('admin.discounts.index') }}" class="btn-secondary inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Discounts
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Discount Details</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="product_id" class="mb-1 block text-sm font-medium text-gray-700">Product <span class="text-red-500">*</span></label>
                    <select name="product_id" id="product_id" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500" onchange="calculateDiscount()">
                        <option value="" data-price="0">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $discount->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ number_format($product->price, 2) }} MDL)
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_percent" class="mb-1 block text-sm font-medium text-gray-700">Discount Percentage <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', $discount->discount_percent) }}" min="1" max="99" step="1" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 pr-10 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500" oninput="calculateDiscount()">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">%</span>
                    </div>
                    @error('discount_percent')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Calculated Price</label>
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2">
                        <span id="original-price" class="text-sm text-gray-400 line-through">{{ number_format($discount->original_price, 2) }} MDL</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <span id="discounted-price" class="text-sm font-bold text-green-700">{{ number_format($discount->discounted_price, 2) }} MDL</span>
                    </div>
                </div>

                <div>
                    <label for="starts_at" class="mb-1 block text-sm font-medium text-gray-700">Starts At <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $discount->starts_at->format('Y-m-d\TH:i')) }}" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ends_at" class="mb-1 block text-sm font-medium text-gray-700">Ends At <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $discount->ends_at->format('Y-m-d\TH:i')) }}" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-kinder-600 focus:ring-kinder-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.discounts.index') }}" class="btn-secondary rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="btn-primary rounded-lg bg-kinder-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
                Update Discount
            </button>
        </div>
    </form>
</div>

<script>
    function calculateDiscount() {
        const productSelect = document.getElementById('product_id');
        const discountInput = document.getElementById('discount_percent');
        const originalPriceEl = document.getElementById('original-price');
        const discountedPriceEl = document.getElementById('discounted-price');

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const percent = parseFloat(discountInput.value) || 0;

        const discountedPrice = price - (price * percent / 100);

        originalPriceEl.textContent = price.toFixed(2) + ' MDL';
        discountedPriceEl.textContent = discountedPrice.toFixed(2) + ' MDL';
    }

    document.addEventListener('DOMContentLoaded', calculateDiscount);
</script>
@endsection
