@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-kinder-100 text-xl font-bold text-kinder-700">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- User Info --}}
        <div>
            <div class="admin-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">User Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Full Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Email</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Phone</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Address</p>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($user->address)
                                {{ $user->address }}
                                @if($user->city)
                                    <br>{{ $user->city }}{{ $user->postal_code ? ', ' . $user->postal_code : '' }}
                                @endif
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Role</p>
                        <p class="mt-1">
                            @if($user->is_admin)
                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">Admin</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Customer</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Registered</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Email Verified</p>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center gap-1 text-green-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $user->email_verified_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-yellow-600">Not verified</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders History --}}
        <div class="lg:col-span-2">
            <div class="admin-card overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Order History</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $user->orders->count() }} total orders</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-3 font-medium text-gray-500">Order #</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Status</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Total</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Date</th>
                                <th class="px-6 py-3 font-medium text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($user->orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>
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
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ number_format($order->total, 2) }} MDL</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-kinder-600" title="View Order">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <p class="text-lg font-medium">No orders yet.</p>
                                        <p class="mt-1 text-sm">This user has not placed any orders.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
