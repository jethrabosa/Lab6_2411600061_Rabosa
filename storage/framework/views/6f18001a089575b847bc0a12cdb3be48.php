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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product: <?php echo e($product->name); ?></h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Overview</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between"><dt class="text-gray-500">SKU</dt><dd class="font-medium text-gray-900"><?php echo e($product->sku); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Category</dt><dd class="font-medium text-gray-900"><?php echo e($product->category); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Quantity</dt><dd class="font-medium text-gray-900"><?php echo e($product->quantity); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Reorder Level</dt><dd class="font-medium text-gray-900"><?php echo e($product->reorder_level); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Unit Cost</dt><dd class="font-medium text-gray-900"><?php echo e(number_format($product->unit_cost, 2)); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Inventory Value</dt><dd class="font-medium text-gray-900"><?php echo e(number_format($product->quantity * $product->unit_cost, 2)); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold text-white <?php echo e($product->stock_status_color === 'red' ? 'bg-red-600' : ($product->stock_status_color === 'amber' ? 'bg-amber-600' : 'bg-emerald-600')); ?>"><?php echo e($product->stock_status); ?></span></dd></div>
                    </dl>
                    <div class="mt-6 text-sm text-gray-600"><?php echo e($product->description ?: 'No description provided.'); ?></div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Transaction History</h3>
                        <a href="<?php echo e(route('products.edit', $product)); ?>" class="text-sm text-indigo-600 hover:underline">Edit Product</a>
                    </div>
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                        <div>
                            <label for="from" class="block text-xs font-medium text-gray-600">From</label>
                            <input id="from" name="from" type="date" value="<?php echo e($validated['from'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label for="to" class="block text-xs font-medium text-gray-600">To</label>
                            <input id="to" name="to" type="date" value="<?php echo e($validated['to'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filter</button>
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="rounded-md border px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Clear</a>
                        </div>
                    </form>
                    <?php if($transactions->isEmpty()): ?>
                        <p class="text-gray-500">No transactions recorded for this product in this date range.</p>
                    <?php else: ?>
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
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-2 pr-2 text-gray-600"><?php echo e($transaction->created_at->format('M d, Y H:i')); ?></td>
                                        <td class="py-2 pr-2 text-gray-900"><?php echo e(str_replace('_', ' ', ucfirst($transaction->type))); ?></td>
                                        <td class="py-2 pr-2 text-right <?php echo e($transaction->type === 'stock_out' || ($transaction->type === 'adjustment' && $transaction->quantity < 0) ? 'text-red-600' : 'text-green-600'); ?>">
                                            <?php echo e($transaction->type === 'stock_out' || ($transaction->type === 'adjustment' && $transaction->quantity < 0) ? '' : '+'); ?><?php echo e($transaction->type === 'stock_out' ? -$transaction->quantity : $transaction->quantity); ?>

                                        </td>
                                        <td class="py-2 pr-2 text-right font-medium text-gray-900"><?php echo e($transaction->running_balance); ?></td>
                                        <td class="py-2 text-gray-600"><?php echo e($transaction->user?->name ?? 'System'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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
<?php /**PATH C:\xampp\htdocs\Lab6_2411600061\resources\views/products/show.blade.php ENDPATH**/ ?>