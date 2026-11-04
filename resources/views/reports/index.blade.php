<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Reports</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="text-sm text-gray-500">Inventory Valuation</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format((float) $inventoryValue, 2) }}</div>
                    <p class="mt-1 text-xs text-gray-500">Current quantity × unit cost</p>
                </div>
                <div class="rounded-lg bg-white p-6 shadow md:col-span-2">
                    <div class="text-sm text-gray-500">Low Stock Report</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($lowStockProducts as $product)
                            <a href="{{ route('products.show', $product) }}" class="rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-900 hover:bg-amber-200">
                                {{ $product->name }} · {{ $product->quantity }}/{{ $product->reorder_level }}
                            </a>
                        @empty
                            <span class="text-sm text-gray-500">All products are above their reorder levels.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Stock Movement Report</h3>
                        <p class="text-sm text-gray-500">Filter inventory transactions by date and export the results as CSV.</p>
                    </div>
                    <a href="{{ route('reports.export', request()->query()) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Export CSV</a>
                </div>
                <form method="GET" class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                        <input id="from" name="from" type="date" value="{{ $validated['from'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                        <input id="to" name="to" type="date" value="{{ $validated['to'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filter</button>
                        <a href="{{ route('reports.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Clear</a>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">Date</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">Product</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">Type</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500">Quantity</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">Reference</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">User</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-3 py-2 text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-3 py-2 text-gray-900">{{ $transaction->product?->name ?? 'Unknown product' }}</td>
                                <td class="px-3 py-2">{{ str_replace('_', ' ', $transaction->type) }}</td>
                                <td class="px-3 py-2 text-right">{{ $transaction->signed_quantity > 0 ? '+' : '' }}{{ $transaction->signed_quantity }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $transaction->reference_number ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $transaction->user?->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-3 py-6 text-center text-gray-500">No transactions found for this date range.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $transactions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
