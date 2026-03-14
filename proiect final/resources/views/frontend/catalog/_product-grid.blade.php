@if ($products->count())
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 md:gap-8">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
@else
    <x-empty-state
        icon="search"
        title="Nu am găsit jucării"
        message="Încearcă să ajustezi filtrele sau termenii de căutare pentru a găsi ceea ce cauți."
        :actionUrl="route('catalog.index')"
        actionLabel="Resetează filtrele"
    />
@endif
