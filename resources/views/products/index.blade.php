<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-sm font-semibold text-white">Add Product</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900"><a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:underline">{{ $product->name }}</a></td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->sku }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->quantity }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold text-white {{ $product->stock_status_color === 'red' ? 'bg-red-600' : ($product->stock_status_color === 'amber' ? 'bg-amber-600' : 'bg-emerald-600') }}">
                                        {{ $product->stock_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
