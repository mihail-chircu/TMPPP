@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Product</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new product for the toy store.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Products
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name if left empty" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sku" class="mb-1 block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand" class="mb-1 block text-sm font-medium text-gray-700">Brand</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Price (MDL) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Description</h2>
            <div class="space-y-4">
                <div>
                    <label for="short_description" class="mb-1 block text-sm font-medium text-gray-700">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="2" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Full Description</label>
                    <textarea name="description" id="description" rows="6" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Age Range & Stock --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Details</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label for="age_min" class="mb-1 block text-sm font-medium text-gray-700">Minimum Age</label>
                    <input type="number" name="age_min" id="age_min" value="{{ old('age_min') }}" min="0" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('age_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="age_max" class="mb-1 block text-sm font-medium text-gray-700">Maximum Age</label>
                    <input type="number" name="age_max" id="age_max" value="{{ old('age_max') }}" min="0" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('age_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="mb-1 block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Options --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Options</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-kinder-600 focus:ring-kinder-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-kinder-600 focus:ring-kinder-500">
                    <label for="is_featured" class="text-sm font-medium text-gray-700">Featured</label>
                </div>

                <div>
                    <label for="badge" class="mb-1 block text-sm font-medium text-gray-700">Badge</label>
                    <select name="badge" id="badge" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                        <option value="" {{ old('badge') == '' ? 'selected' : '' }}>None</option>
                        <option value="new" {{ old('badge') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="hot" {{ old('badge') == 'hot' ? 'selected' : '' }}>Hot</option>
                    </select>
                    @error('badge')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Images</h2>
            <div>
                <label for="images" class="mb-1 block text-sm font-medium text-gray-700">Product Images</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-kinder-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-kinder-700 hover:file:bg-kinder-100 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">You can select multiple images. Accepted formats: JPG, PNG, GIF, WebP.</p>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- SEO --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">SEO</h2>
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="2" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="btn-primary rounded-lg bg-kinder-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
