<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stock Adjustment</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <p class="mb-6 text-sm text-gray-600">Enter the physical count. The system records the difference as an auditable adjustment transaction.</p>

                <form method="POST" action="{{ route('transactions.adjustment.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-quantity="{{ $product->quantity }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                            <p id="system-quantity" class="mt-2 text-sm text-gray-500">Choose a product to see its current system quantity.</p>
                        </div>

                        <div>
                            <label for="physical_count" class="block text-sm font-medium text-gray-700">Physical Count</label>
                            <input id="physical_count" name="physical_count" type="number" min="0" value="{{ old('physical_count') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <select id="reason" name="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select a reason</option>
                                @foreach(['physical_count' => 'Physical count discrepancy', 'damaged' => 'Damaged goods', 'loss' => 'Theft or loss', 'system_error' => 'System error', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('reason') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="4" maxlength="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-indigo-500">Record Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const quantityText = document.getElementById('system-quantity');
        const updateSystemQuantity = () => {
            const option = productSelect.options[productSelect.selectedIndex];
            quantityText.textContent = option.dataset.quantity === undefined
                ? 'Choose a product to see its current system quantity.'
                : `Current system quantity: ${option.dataset.quantity}`;
        };
        productSelect.addEventListener('change', updateSystemQuantity);
        updateSystemQuantity();
    </script>
</x-app-layout>
