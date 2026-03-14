@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">Welcome back to Kinder Admin</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Orders --}}
        <div class="admin-card flex items-center justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-kinder-100">
                <svg class="h-6 w-6 text-kinder-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="admin-card flex items-center justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500">Revenue</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} MDL</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Products --}}
        <div class="admin-card flex items-center justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500">Products</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        {{-- Users --}}
        <div class="admin-card flex items-center justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500">Users</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2">
            <div class="admin-card rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-3 font-medium text-gray-500">Order #</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Customer</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Total</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $order->customer_name }}</td>
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
                                    <td class="px-6 py-4 text-gray-700">{{ number_format($order->total, 2) }} MDL</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div>
            <div class="admin-card rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Low Stock Alert</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between px-6 py-3">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-3 inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-800">
                                {{ $product->stock }} left
                            </span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">All products are well stocked.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
