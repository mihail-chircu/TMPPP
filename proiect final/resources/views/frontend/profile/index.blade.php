@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <x-breadcrumb :items="[['label' => 'Profilul meu']]" />

        <h1 class="section-title mb-8">Profilul Meu</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Profile Information Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-kinder-brown-100/60 shadow-soft p-5 sticky top-24">

                    {{-- Avatar --}}
                    <div class="text-center mb-5">
                        <div class="w-16 h-16 mx-auto rounded-full bg-kinder-500 flex items-center justify-center mb-2.5">
                            <span class="text-xl font-display font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <h2 class="font-display text-base font-bold text-kinder-brown-800">{{ $user->name }}</h2>
                        <p class="text-xs text-kinder-brown-400">{{ $user->email }}</p>
                    </div>

                    <hr class="border-kinder-brown-100 mb-5">

                    {{-- Profile Edit Form --}}
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-3.5">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Nume complet</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="input-field !text-sm" required>
                                @error('name') <p class="mt-1 text-xs text-kinder-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="input-field !text-sm" required>
                                @error('email') <p class="mt-1 text-xs text-kinder-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Telefon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field !text-sm" placeholder="078 XXX XXX">
                                @error('phone') <p class="mt-1 text-xs text-kinder-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Adresa</label>
                                <textarea id="address" name="address" rows="2" class="input-field !text-sm resize-none" placeholder="Adresa ta de livrare (str., nr.)">{{ old('address', $user->address) }}</textarea>
                                @error('address') <p class="mt-1 text-xs text-kinder-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="city" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Orașul</label>
                                    <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" class="input-field !text-sm" placeholder="Chișinău">
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-xs font-semibold text-kinder-brown-600 uppercase tracking-wider mb-1.5">Cod poștal</label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="input-field !text-sm" placeholder="MD-2001">
                                </div>
                            </div>

                            <button type="submit" class="btn-primary w-full text-sm mt-1">
                                Actualizează profilul
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Order History --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-kinder-brown-100/60 shadow-soft overflow-hidden">
                    <div class="p-5 border-b border-kinder-brown-100">
                        <h2 class="font-display text-base font-bold text-kinder-brown-800 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-kinder-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Istoric Comenzi
                        </h2>
                    </div>

                    @if ($orders->isEmpty())
                        <x-empty-state
                            icon="order"
                            title="Nicio comandă încă"
                            message="Când plasezi prima comandă, aceasta va apărea aici."
                            :actionUrl="route('catalog.index')"
                            actionLabel="Începe cumpărăturile"
                        />
                    @else
                        {{-- Desktop Table --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-kinder-brown-50 border-b border-kinder-brown-100">
                                        <th class="px-5 py-3 text-left text-xs font-display font-bold text-kinder-brown-500 uppercase tracking-wider">Comandă</th>
                                        <th class="px-4 py-3 text-left text-xs font-display font-bold text-kinder-brown-500 uppercase tracking-wider">Data</th>
                                        <th class="px-4 py-3 text-center text-xs font-display font-bold text-kinder-brown-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-display font-bold text-kinder-brown-500 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-3 text-right text-xs font-display font-bold text-kinder-brown-500 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-kinder-brown-50">
                                    @foreach ($orders as $order)
                                        <tr class="hover:bg-kinder-brown-50/50 transition-colors">
                                            <td class="px-5 py-3.5">
                                                <span class="font-display font-bold text-kinder-brown-800 text-sm">{{ $order->order_number }}</span>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <span class="text-sm text-kinder-brown-500">{{ $order->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                @php
                                                    $statusColors = match ($order->status) {
                                                        'pending' => 'bg-yellow-50 text-yellow-700',
                                                        'processing' => 'bg-kinder-50 text-kinder-700',
                                                        'shipped' => 'bg-purple-50 text-purple-700',
                                                        'delivered' => 'bg-green-50 text-green-700',
                                                        'cancelled' => 'bg-red-50 text-red-700',
                                                        default => 'bg-kinder-brown-50 text-kinder-brown-600',
                                                    };
                                                    $statusLabels = match ($order->status) {
                                                        'pending' => 'În așteptare',
                                                        'processing' => 'În procesare',
                                                        'shipped' => 'Expediată',
                                                        'delivered' => 'Livrată',
                                                        'cancelled' => 'Anulată',
                                                        default => ucfirst($order->status),
                                                    };
                                                @endphp
                                                <span class="{{ $statusColors }} inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-display font-bold uppercase tracking-wide">
                                                    {{ $statusLabels }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3.5 text-right">
                                                <span class="font-display font-bold text-kinder-brown-800 text-sm">{{ number_format($order->total, 0) }} lei</span>
                                            </td>
                                            <td class="px-4 py-3.5 text-right">
                                                <a href="{{ route('checkout.confirmation', $order) }}" class="text-kinder-500 hover:text-kinder-600 text-xs font-semibold transition-colors">
                                                    Vezi
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="md:hidden p-4 space-y-3">
                            @foreach ($orders as $order)
                                <a href="{{ route('checkout.confirmation', $order) }}" class="block bg-kinder-brown-50 rounded-xl p-3.5 hover:bg-kinder-brown-100/50 transition-colors">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="font-display font-bold text-kinder-brown-800 text-sm">{{ $order->order_number }}</span>
                                        @php
                                            $statusColors = match ($order->status) {
                                                'pending' => 'bg-yellow-50 text-yellow-700',
                                                'processing' => 'bg-kinder-50 text-kinder-700',
                                                'shipped' => 'bg-purple-50 text-purple-700',
                                                'delivered' => 'bg-green-50 text-green-700',
                                                'cancelled' => 'bg-red-50 text-red-700',
                                                default => 'bg-kinder-brown-100 text-kinder-brown-600',
                                            };
                                            $statusLabels = match ($order->status) {
                                                'pending' => 'În așteptare',
                                                'processing' => 'În procesare',
                                                'shipped' => 'Expediată',
                                                'delivered' => 'Livrată',
                                                'cancelled' => 'Anulată',
                                                default => ucfirst($order->status),
                                            };
                                        @endphp
                                        <span class="{{ $statusColors }} inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-display font-bold uppercase">
                                            {{ $statusLabels }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-kinder-brown-400 text-xs">{{ $order->created_at->format('d M Y') }}</span>
                                        <span class="font-display font-bold text-kinder-brown-800">{{ number_format($order->total, 0) }} lei</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if ($orders->hasPages())
                            <div class="px-5 py-4 border-t border-kinder-brown-100">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
