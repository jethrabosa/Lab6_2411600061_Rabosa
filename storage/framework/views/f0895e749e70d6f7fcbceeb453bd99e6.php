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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Inventory Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div
        class="py-12"
        id="inventory-dashboard"
        data-stats-url="<?php echo e(url('/api/dashboard/stats')); ?>"
        data-low-stock-url="<?php echo e(url('/api/products/low-stock')); ?>"
        data-recent-url="<?php echo e(url('/api/transactions/recent')); ?>"
        data-product-url="<?php echo e(url('/products/__id__')); ?>"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Total Products</div>
                    <div id="total-products" class="mt-2 text-3xl font-bold text-gray-900"><?php echo e($totalProducts); ?></div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Low Stock Items</div>
                    <div id="low-stock-count" class="mt-2 text-3xl font-bold text-amber-600"><?php echo e($lowStockProducts); ?></div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500">Units on Hand</div>
                    <div id="total-quantity" class="mt-2 text-3xl font-bold text-emerald-600"><?php echo e($totalQuantity); ?></div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Low Stock Alerts</h3>
                        <a href="<?php echo e(route('reports.index')); ?>" class="text-sm text-indigo-600 hover:underline">View reports</a>
                    </div>
                    <div id="low-stock-list" class="flex flex-wrap gap-2">
                        <?php $__empty_1 = true; $__currentLoopData = \App\Models\Product::lowStock()->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-900 hover:bg-amber-200">
                                <?php echo e($product->name); ?> · <?php echo e($product->quantity); ?>/<?php echo e($product->reorder_level); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-sm text-gray-500">No low-stock products.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Recent Transactions</h3>
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('transactions.adjustment.create')); ?>" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">Adjust Stock</a>
                            <a href="<?php echo e(route('transactions.create')); ?>" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-indigo-500">New Transaction</a>
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
                                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-600"><?php echo e($transaction->created_at->format('M d, Y')); ?></td>
                                        <td class="px-3 py-2 text-sm text-gray-900"><a href="<?php echo e(route('products.show', $transaction->product)); ?>" class="text-indigo-600 hover:underline"><?php echo e($transaction->product?->name ?? 'Unknown product'); ?></a></td>
                                        <td class="px-3 py-2 text-sm"><span class="inline-flex items-center rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-800"><?php echo e(str_replace('_', ' ', $transaction->type)); ?></span></td>
                                        <td class="px-3 py-2 text-sm font-medium <?php echo e($transaction->signed_quantity < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                            <?php echo e($transaction->signed_quantity > 0 ? '+' : ''); ?><?php echo e($transaction->signed_quantity); ?>

                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-600"><?php echo e($transaction->user?->name ?? 'System'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span id="dashboard-status" role="status">Dashboard refreshes every 30 seconds.</span>
                        <span>Last updated: <time id="dashboard-updated"><?php echo e(now()->format('M d, Y H:i:s')); ?></time></span>
                    </div>
                </div>
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
<?php /**PATH C:\xampp\htdocs\Lab6_2411600061\resources\views/dashboard.blade.php ENDPATH**/ ?>