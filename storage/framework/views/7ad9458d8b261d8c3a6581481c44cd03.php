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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stock Adjustment</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <?php if($errors->any()): ?>
                    <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <p class="mb-6 text-sm text-gray-600">Enter the physical count. The system records the difference as an auditable adjustment transaction.</p>

                <form method="POST" action="<?php echo e(route('transactions.adjustment.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-6">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select a product</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($product->id); ?>" data-quantity="<?php echo e($product->quantity); ?>" <?php echo e(old('product_id') == $product->id ? 'selected' : ''); ?>>
                                        <?php echo e($product->name); ?> (<?php echo e($product->sku); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p id="system-quantity" class="mt-2 text-sm text-gray-500">Choose a product to see its current system quantity.</p>
                        </div>

                        <div>
                            <label for="physical_count" class="block text-sm font-medium text-gray-700">Physical Count</label>
                            <input id="physical_count" name="physical_count" type="number" min="0" value="<?php echo e(old('physical_count')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <select id="reason" name="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select a reason</option>
                                <?php $__currentLoopData = ['physical_count' => 'Physical count discrepancy', 'damaged' => 'Damaged goods', 'loss' => 'Theft or loss', 'system_error' => 'System error', 'other' => 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('reason') === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="4" maxlength="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="<?php echo e(route('transactions.index')); ?>" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
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
<?php /**PATH C:\xampp\htdocs\Lab6_2411600061\resources\views/transactions/adjustment.blade.php ENDPATH**/ ?>