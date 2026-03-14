@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Discounts</h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn-primary inline-flex items-center rounded-lg bg-kinder-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Discount
        </a>
    </div>

    {{-- Discounts Table --}}
    <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 font-medium text-gray-500">Product</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Discount %</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Original Price</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Discounted Price</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Starts</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Ends</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($discounts as $discount)
                        @php
                            $now = now();
                            $isRunning = $discount->is_active && $discount->starts_at->lte($now) && $discount->ends_at->gte($now);
                            $isScheduled = $discount->is_active && $discount->starts_at->gt($now);
                            $isExpired = $discount->ends_at->lt($now);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $discount->product?->name ?? 'Deleted Product' }}</td>
                            <td class="px-6 py-4">
                                <span class="badge-sale inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-800">-{{ $discount->discount_percent }}%</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format($discount->original_price, 2) }} MDL</td>
                            <td class="px-6 py-4 font-medium text-green-700">{{ number_format($discount->discounted_price, 2) }} MDL</td>
                            <td class="px-6 py-4 text-gray-500">{{ $discount->starts_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $discount->ends_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    @if(!$discount->is_active)
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Inactive</span>
                                    @elseif($isRunning)
                                        <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                        <span class="inline-flex items-center gap-1 text-xs text-green-600">
                                            <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
                                            Running now
                                        </span>
                                    @elseif($isScheduled)
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Scheduled</span>
                                    @elseif($isExpired)
                                        <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Expired</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-kinder-600" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this discount?');">
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-medium">No discounts found.</p>
                                <p class="mt-1 text-sm">Create your first discount to start promoting products.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($discounts->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $discounts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
