<nav class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8">
        <a href="<?php echo e(route('dashboard')); ?>" class="text-lg font-bold text-indigo-700">Inventory Tracker</a>
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm font-medium text-gray-600">
            <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-indigo-600">Dashboard</a>
            <a href="<?php echo e(route('products.index')); ?>" class="hover:text-indigo-600">Products</a>
            <a href="<?php echo e(route('transactions.index')); ?>" class="hover:text-indigo-600">Transactions</a>
            <a href="<?php echo e(route('transactions.adjustment.create')); ?>" class="hover:text-indigo-600">Stock Adjustment</a>
            <a href="<?php echo e(route('reports.index')); ?>" class="hover:text-indigo-600">Reports</a>
            <a href="<?php echo e(route('notifications.index')); ?>" class="hover:text-indigo-600">Low Stock Alerts</a>
        </div>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\Lab6_2411600061\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>