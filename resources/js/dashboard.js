const dashboard = document.querySelector('#inventory-dashboard');

if (dashboard) {
    const status = document.querySelector('#dashboard-status');
    const updated = document.querySelector('#dashboard-updated');
    const productsUrl = dashboard.dataset.productUrl;

    const requestJson = async (url) => {
        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(`Dashboard request failed (${response.status}).`);
        }

        return response.json();
    };

    const refresh = async () => {
        try {
            const [stats, lowStock, transactions] = await Promise.all([
                requestJson(dashboard.dataset.statsUrl),
                requestJson(dashboard.dataset.lowStockUrl),
                requestJson(dashboard.dataset.recentUrl),
            ]);

            document.querySelector('#total-products').textContent = stats.total_products;
            document.querySelector('#low-stock-count').textContent = stats.low_stock_products;
            document.querySelector('#total-quantity').textContent = stats.total_quantity;

            const lowStockList = document.querySelector('#low-stock-list');
            lowStockList.replaceChildren();
            if (lowStock.length === 0) {
                const empty = document.createElement('span');
                empty.className = 'text-sm text-gray-500';
                empty.textContent = 'No low-stock products.';
                lowStockList.append(empty);
            } else {
                lowStock.forEach((product) => {
                    const link = document.createElement('a');
                    link.href = productsUrl.replace('__id__', product.id);
                    link.className = 'rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-900 hover:bg-amber-200';
                    link.textContent = `${product.name} · ${product.quantity}/${product.reorder_level}`;
                    lowStockList.append(link);
                });
            }

            const tableBody = document.querySelector('#recent-transactions');
            tableBody.replaceChildren();
            transactions.forEach((transaction) => {
                const row = document.createElement('tr');
                const values = [
                    transaction.created_at,
                    transaction.product_name,
                    transaction.type.replaceAll('_', ' '),
                    `${transaction.signed_quantity > 0 ? '+' : ''}${transaction.signed_quantity}`,
                    transaction.user_name,
                ];

                values.forEach((value, index) => {
                    const cell = document.createElement('td');
                    cell.className = `px-3 py-2 text-sm ${index === 3 && transaction.signed_quantity < 0 ? 'text-red-600' : 'text-gray-600'}`;
                    if (index === 1) {
                        const link = document.createElement('a');
                        link.href = productsUrl.replace('__id__', transaction.product_id);
                        link.className = 'text-indigo-600 hover:underline';
                        link.textContent = value;
                        cell.append(link);
                    } else {
                        cell.textContent = value;
                    }
                    row.append(cell);
                });
                tableBody.append(row);
            });

            updated.textContent = new Date(stats.updated_at).toLocaleString();
            status.textContent = 'Dashboard refreshes every 30 seconds.';
        } catch (error) {
            status.textContent = `${error.message} Retrying in 30 seconds.`;
        }
    };

    window.setInterval(refresh, 30000);
}
