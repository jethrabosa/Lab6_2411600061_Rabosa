<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction Details</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Transaction</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-900">{{ str_replace('_', ' ', $inventoryTransaction->type) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Quantity</dt><dd class="font-medium {{ $inventoryTransaction->signed_quantity < 0 ? 'text-red-600' : 'text-green-600' }}">{{ $inventoryTransaction->signed_quantity > 0 ? '+' : '' }}{{ $inventoryTransaction->signed_quantity }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Reference</dt><dd class="font-medium text-gray-900">{{ $inventoryTransaction->reference_number ?? '—' }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">User</dt><dd class="font-medium text-gray-900">{{ $inventoryTransaction->user?->name ?? 'System' }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Date</dt><dd class="font-medium text-gray-900">{{ $inventoryTransaction->created_at->format('M d, Y H:i') }}</dd></div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Product</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Name</dt><dd class="font-medium text-gray-900"><a href="{{ route('products.show', $inventoryTransaction->product) }}" class="text-indigo-600 hover:underline">{{ $inventoryTransaction->product->name }}</a></dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">SKU</dt><dd class="font-medium text-gray-900">{{ $inventoryTransaction->product->sku }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Available Qty</dt><dd class="font-medium text-gray-900">{{ $inventoryTransaction->product->quantity }}</dd></div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold mb-2">Notes</h3>
                    <p class="text-gray-600">{{ $inventoryTransaction->notes ?: 'No notes for this transaction.' }}</p>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-100">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
