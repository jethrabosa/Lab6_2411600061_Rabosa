<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Inventory Transaction</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->quantity }} available)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select a type</option>
                                <option value="stock_in" {{ old('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                                <option value="stock_out" {{ old('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                                <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                            <input id="reference_number" name="reference_number" type="text" value="{{ old('reference_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Save Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
