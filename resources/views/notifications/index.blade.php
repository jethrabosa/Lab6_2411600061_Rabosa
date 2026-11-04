<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Low Stock Alerts</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto space-y-4 sm:px-6 lg:px-8">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}" class="block rounded-lg border-l-4 border-amber-400 bg-white p-5 shadow-sm hover:bg-amber-50">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">SKU {{ $product->sku }} · Reorder level {{ $product->reorder_level }}</p>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-900">
                            {{ $product->quantity }} in stock
                        </span>
                    </div>
                </a>
            @empty
                <div class="rounded-lg bg-white p-8 text-center text-gray-500 shadow-sm">No products are currently at or below their reorder level.</div>
            @endforelse
            <div>{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
