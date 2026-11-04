<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Transactions</h2>
            <div class="flex gap-2">
                <a href="{{ route('transactions.adjustment.create') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Adjust Stock</a>
                <a href="{{ route('transactions.create') }}" class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-sm font-semibold text-white">New Transaction</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Types</option>
                            <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                            <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        </select>
                    </div>
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                        <input id="from" name="from" type="date" value="{{ request('from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                        <input id="to" name="to" type="date" value="{{ request('to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center bg-indigo-600 hover:bg-indigo-500 text-white rounded-md px-4 py-2 text-sm font-semibold">Filter</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900"><a href="{{ route('products.show', $transaction->product) }}" class="text-indigo-600 hover:underline">{{ $transaction->product?->name ?? 'N/A' }}</a></td>
                                <td class="px-6 py-4 text-sm"><span class="inline-flex rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-800">{{ str_replace('_', ' ', $transaction->type) }}</span></td>
                                <td class="px-6 py-4 text-sm font-medium {{ $transaction->signed_quantity < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $transaction->signed_quantity > 0 ? '+' : '' }}{{ $transaction->signed_quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->user?->name ?? 'System' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-600 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
