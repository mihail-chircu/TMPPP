@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary inline-flex items-center rounded-lg bg-kinder-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
    </div>

    {{-- Categories Table --}}
    <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 font-medium text-gray-500">Image</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Parent</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Products</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Sort Order</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">
                                    @if($category->parent_id)
                                        <span class="text-gray-400">&mdash;</span>
                                    @endif
                                    {{ $category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $category->parent?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $category->products_count ?? $category->products->count() }}</td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $category->sort_order }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-kinder-600" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                                <p class="text-lg font-medium">No categories found.</p>
                                <p class="mt-1 text-sm">Get started by adding your first category.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
