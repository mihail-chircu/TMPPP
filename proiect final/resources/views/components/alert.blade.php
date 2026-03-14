@props([
    'type' => null,
    'message' => null,
])

@php
    // Auto-detect flash messages if called without explicit props
    $alerts = [];

    if ($message && $type) {
        $alerts[] = ['type' => $type, 'message' => $message];
    } else {
        if (session('success')) {
            $alerts[] = ['type' => 'success', 'message' => session('success')];
        }
        if (session('warning')) {
            $alerts[] = ['type' => 'warning', 'message' => session('warning')];
        }
        if (session('error')) {
            $alerts[] = ['type' => 'error', 'message' => session('error')];
        }
        if (session('info')) {
            $alerts[] = ['type' => 'info', 'message' => session('info')];
        }
    }
@endphp

@foreach ($alerts as $alert)
    @php
        $alertStyles = match ($alert['type']) {
            'success' => 'bg-candy-green/10 border-candy-green/30 text-green-800',
            'warning' => 'bg-candy-yellow/30 border-candy-yellow/50 text-yellow-800',
            'error'   => 'bg-candy-pink/10 border-candy-pink/30 text-red-800',
            default   => 'bg-kinder-500/10 border-kinder-500/30 text-blue-800',
        };

        $alertIcon = match ($alert['type']) {
            'success' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />',
            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            default   => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        };
    @endphp

    <div class="alert-flash max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4"
         style="animation: alertSlideIn 0.3s ease-out">
        <div class="{{ $alertStyles }} border rounded-2xl px-5 py-4 flex items-center gap-3 font-semibold text-sm shadow-sm"
             role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                {!! $alertIcon !!}
            </svg>
            <span class="flex-1">{{ $alert['message'] }}</span>
            <button type="button"
                    class="p-1 rounded-lg opacity-60 hover:opacity-100 transition"
                    onclick="this.closest('.alert-flash').remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endforeach

@if (count($alerts) > 0)
<style>
    @keyframes alertSlideIn {
        from { opacity: 0; transform: translateY(-0.5rem); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
    // Auto-dismiss flash alerts after 3 seconds
    setTimeout(function () {
        document.querySelectorAll('.alert-flash').forEach(function (el) {
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-0.5rem)';
            setTimeout(function () { el.remove(); }, 300);
        });
    }, 3000);
</script>
@endif
