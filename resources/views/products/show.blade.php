<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product: {{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Overview</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between"><dt class="text-gray-500">SKU</dt><dd class="font-medium text-gray-900">{{ $product->sku }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Category</dt><dd class="font-medium text-gray-900">{{ $product->category }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Quantity</dt><dd class="font-medium text-gray-900">{{ $product->quantity }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Reorder Level</dt><dd class="font-medium text-gray-900">{{ $product->reorder_level }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Unit Cost</dt><dd class="font-medium text-gray-900">{{ number_format($product->unit_cost, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Inventory Value</dt><dd class="font-medium text-gray-900">{{ number_format($product->quantity * $product->unit_cost, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold text-white {{ $product->stock_status_color === 'red' ? 'bg-red-600' : ($product->stock_status_color === 'amber' ? 'bg-amber-600' : 'bg-emerald-600') }}">{{ $product->stock_status }}</span></dd></div>
                    </dl>
                    <div class="mt-6 text-sm text-gray-600">{{ $product->description ?: 'No description provided.' }}</div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Transaction History</h3>
                        <a href="{{ route('products.edit', $product) }}" class="text-sm text-indigo-600 hover:underline">Edit Product</a>
                    </div>
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                        <div>
                            <label for="from" class="block text-xs font-medium text-gray-600">From</label>
                            <input id="from" name="from" type="date" value="{{ $validated['from'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label for="to" class="block text-xs font-medium text-gray-600">To</label>
                            <input id="to" name="to" type="date" value="{{ $validated['to'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filter</button>
                            <a href="{{ route('products.show', $product) }}" class="rounded-md border px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Clear</a>
                        </div>
                    </form>
                    @if($transactions->isEmpty())
                        <p class="text-gray-500">No transactions recorded for this product in this date range.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead><tr>
                                    <th class="py-2 text-left font-medium text-gray-500">Date</th>
                                    <th class="py-2 text-left font-medium text-gray-500">Type</th>
                                    <th class="py-2 text-right font-medium text-gray-500">Change</th>
                                    <th class="py-2 text-right font-medium text-gray-500">Balance</th>
                                    <th class="py-2 text-left font-medium text-gray-500">User</th>
                                </tr></thead>
                                <tbody class="divide-y divide-gray-100">
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="py-2 pr-2 text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-2 pr-2 text-gray-900">{{ str_replace('_', ' ', ucfirst($transaction->type)) }}</td>
                                        <td class="py-2 pr-2 text-right {{ $transaction->type === 'stock_out' || ($transaction->type === 'adjustment' && $transaction->quantity < 0) ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $transaction->type === 'stock_out' || ($transaction->type === 'adjustment' && $transaction->quantity < 0) ? '' : '+' }}{{ $transaction->type === 'stock_out' ? -$transaction->quantity : $transaction->quantity }}
                                        </td>
                                        <td class="py-2 pr-2 text-right font-medium text-gray-900">{{ $transaction->running_balance }}</td>
                                        <td class="py-2 text-gray-600">{{ $transaction->user?->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
