@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
            <p class="mt-1 text-sm text-gray-500">Editing: {{ $category->name }}</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-secondary inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Categories
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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Category Details --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Category Details</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" placeholder="Auto-generated from name if left empty" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="parent_id" class="mb-1 block text-sm font-medium text-gray-700">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                        <option value="">None (Root Category)</option>
                        @foreach($parentCategories as $cat)
                            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Category Image</label>
                    @if($category->image)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-16 w-16 rounded-lg object-cover">
                            <span class="text-xs text-gray-500">Current image</span>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-kinder-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-kinder-700 hover:file:bg-kinder-100 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current image.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-6">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-kinder-600 focus:ring-kinder-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">SEO</h2>
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $category->meta_title) }}" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="2" class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">{{ old('meta_description', $category->meta_description) }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="btn-primary rounded-lg bg-kinder-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
