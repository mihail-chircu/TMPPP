@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn-secondary inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Order Details & Status --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Info & Status Update --}}
            <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Order Details</h2>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p>Order Number: <span class="font-medium text-gray-900">{{ $order->order_number }}</span></p>
                            <p>Date: <span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span></p>
                            <p>Payment Method: <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method ?? 'N/A') }}</span></p>
                            <p>Current Status:
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
                            </p>
                        </div>
                    </div>

                    {{-- State Pattern: only show allowed transitions --}}
                    @if(count($order->state()->allowedTransitions()) > 0)
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-end gap-2">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="status" class="mb-1 block text-xs font-medium text-gray-500">Update Status</label>
                                <select name="status" id="status" class="input-field rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-kinder-500 focus:outline-none focus:ring-1 focus:ring-kinder-500">
                                    @foreach($order->state()->allowedTransitions() as $transition)
                                        <option value="{{ $transition }}">{{ ucfirst($transition) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn-primary rounded-lg bg-kinder-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-kinder-700">
                                Update
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-gray-400 italic">Status final — nu poate fi modificat</span>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-3 font-medium text-gray-500">Product</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Price</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Discount Price</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Qty</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-500">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                        @if($item->product)
                                            <div class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ number_format($item->price, 2) }} MDL</td>
                                    <td class="px-6 py-4">
                                        @if($item->discount_price && $item->discount_price < $item->price)
                                            <span class="font-medium text-red-600">{{ number_format($item->discount_price, 2) }} MDL</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">{{ number_format($item->total, 2) }} MDL</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Order Totals --}}
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="ml-auto max-w-xs space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->subtotal, 2) }} MDL</span>
                        </div>
                        @if($order->discount_total > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="font-medium text-red-600">-{{ number_format($order->discount_total, 2) }} MDL</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->shipping_cost ?? 0, 2) }} MDL</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-300 pt-2 text-base">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="font-bold text-gray-900">{{ number_format($order->total, 2) }} MDL</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-2 text-lg font-semibold text-gray-900">Order Notes</h2>
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Customer Info Sidebar --}}
        <div>
            <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Customer Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Email</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_email }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Phone</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Shipping Address</p>
                        <div class="mt-1 text-sm text-gray-900">
                            <p>{{ $order->shipping_address }}</p>
                            @if($order->shipping_city)
                                <p>{{ $order->shipping_city }}{{ $order->shipping_postal_code ? ', ' . $order->shipping_postal_code : '' }}</p>
                            @endif
                        </div>
                    </div>

                    @if($order->user)
                        <div class="border-t border-gray-200 pt-4">
                            <a href="{{ route('admin.users.show', $order->user) }}" class="inline-flex items-center text-sm font-medium text-kinder-600 hover:text-kinder-800">
                                View Customer Profile
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
