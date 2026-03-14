@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.export', 'csv') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.orders.export', 'json') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export JSON
            </a>
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="admin-card rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="{{ !request('status') ? 'bg-kinder-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                All
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="{{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Pending
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="{{ request('status') === 'processing' ? 'bg-kinder-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Processing
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="{{ request('status') === 'shipped' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Shipped
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="{{ request('status') === 'delivered' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Delivered
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="{{ request('status') === 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Cancelled
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="admin-card rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number, customer name, or email..." class="input-field w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                </div>
                <button type="submit" class="btn-secondary rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Search
                </button>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 font-medium text-gray-500">Order #</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Customer</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Items</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Total</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->customer_email }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $order->items_count ?? $order->items->count() }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ number_format($order->total, 2) }} MDL</td>
                            <td class="px-6 py-4">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex rounded-full bg-kinder-100 px-2.5 py-0.5 text-xs font-medium text-kinder-800">Processing</span>
                                        @break
                                    @case('shipped')
                                        <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">Shipped</span>
                                        @break
                                    @case('delivered')
                                        <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Delivered</span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Cancelled</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-kinder-600" title="View">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-medium">No orders found.</p>
                                <p class="mt-1 text-sm">Orders will appear here once customers start purchasing.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
