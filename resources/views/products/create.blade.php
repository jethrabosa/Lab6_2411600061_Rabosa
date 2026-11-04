<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                            <input id="sku" name="sku" type="text" value="{{ old('sku') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <input id="category" name="category" type="text" value="{{ old('category') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                            <input id="reorder_level" name="reorder_level" type="number" min="0" value="{{ old('reorder_level', 10) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
                            <input id="unit_cost" name="unit_cost" type="number" min="0" step="0.01" value="{{ old('unit_cost', '0.00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Initial Quantity</label>
                            <input id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
