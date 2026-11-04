<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Transactions</h2>
            <div class="flex gap-2">
                <a href="<?php echo e(route('transactions.adjustment.create')); ?>" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Adjust Stock</a>
                <a href="<?php echo e(route('transactions.create')); ?>" class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-sm font-semibold text-white">New Transaction</a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Products</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>" <?php echo e(request('product_id') == $product->id ? 'selected' : ''); ?>><?php echo e($product->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">All Types</option>
                            <option value="stock_in" <?php echo e(request('type') == 'stock_in' ? 'selected' : ''); ?>>Stock In</option>
                            <option value="stock_out" <?php echo e(request('type') == 'stock_out' ? 'selected' : ''); ?>>Stock Out</option>
                            <option value="adjustment" <?php echo e(request('type') == 'adjustment' ? 'selected' : ''); ?>>Adjustment</option>
                        </select>
                    </div>
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                        <input id="from" name="from" type="date" value="<?php echo e(request('from')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                        <input id="to" name="to" type="date" value="<?php echo e(request('to')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($transaction->created_at->format('M d, Y H:i')); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><a href="<?php echo e(route('products.show', $transaction->product)); ?>" class="text-indigo-600 hover:underline"><?php echo e($transaction->product?->name ?? 'N/A'); ?></a></td>
                                <td class="px-6 py-4 text-sm"><span class="inline-flex rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-800"><?php echo e(str_replace('_', ' ', $transaction->type)); ?></span></td>
                                <td class="px-6 py-4 text-sm font-medium <?php echo e($transaction->signed_quantity < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                    <?php echo e($transaction->signed_quantity > 0 ? '+' : ''); ?><?php echo e($transaction->signed_quantity); ?>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($transaction->user?->name ?? 'System'); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <a href="<?php echo e(route('transactions.show', $transaction)); ?>" class="text-indigo-600 hover:underline">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <?php echo e($transactions->links()); ?>

            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\Lab6_2411600061\resources\views/transactions/index.blade.php ENDPATH**/ ?>