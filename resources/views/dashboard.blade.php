<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory Dashboard') }}
        </h2>
    </x-slot>

    <div
        class="py-12"
        id="inventory-dashboard"
        data-stats-url="{{ url('/api/dashboard/stats') }}"
        data-low-stock-url="{{ url('/api/products/low-stock') }}"
        data-recent-url="{{ url('/api/transactions/recent') }}"
        data-product-url="{{ url('/products/__id__') }}"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Total Products</div>
                    <div id="total-products" class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Low Stock Items</div>
                    <div id="low-stock-count" class="mt-2 text-3xl font-bold text-amber-600">{{ $lowStockProducts }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Units on Hand</div>
                    <div id="total-quantity" class="mt-2 text-3xl font-bold text-emerald-600">{{ $totalQuantity }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Low Stock Alerts</h3>
                        <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:underline">View reports</a>
                    </div>
                    <div id="low-stock-list" class="flex flex-wrap gap-2">
                        @forelse(\App\Models\Product::lowStock()->orderBy('name')->get() as $product)
                            <a href="{{ route('products.show', $product) }}" class="rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-900 hover:bg-amber-200">
                                {{ $product->name }} · {{ $product->quantity }}/{{ $product->reorder_level }}
                            </a>
                        @empty
                            <span class="text-sm text-gray-500">No low-stock products.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Recent Transactions</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('transactions.adjustment.create') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">Adjust Stock</a>
                            <a href="{{ route('transactions.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-indigo-500">New Transaction</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Qty</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                                </tr>
                                </thead>
                                <tbody id="recent-transactions" class="divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900"><a href="{{ route('products.show', $transaction->product) }}" class="text-indigo-600 hover:underline">{{ $transaction->product?->name ?? 'Unknown product' }}</a></td>
                                        <td class="px-3 py-2 text-sm"><span class="inline-flex items-center rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-800">{{ str_replace('_', ' ', $transaction->type) }}</span></td>
                                        <td class="px-3 py-2 text-sm font-medium {{ $transaction->signed_quantity < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $transaction->signed_quantity > 0 ? '+' : '' }}{{ $transaction->signed_quantity }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-600">{{ $transaction->user?->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span id="dashboard-status" role="status">Dashboard refreshes every 30 seconds.</span>
                        <span>Last updated: <time id="dashboard-updated">{{ now()->format('M d, Y H:i:s') }}</time></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
