<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $transactions = $this->filteredTransactions($validated)->paginate(20)->withQueryString();
        $lowStockProducts = Product::lowStock()->orderBy('name')->get();
        $inventoryValue = Product::query()->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')->value('total');

        return view('reports.index', compact('transactions', 'lowStockProducts', 'inventoryValue', 'validated'));
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        return response()->streamDownload(function () use ($validated): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Date', 'Product', 'SKU', 'Type', 'Signed Change', 'Reference', 'User', 'Notes']);

            $this->filteredTransactions($validated)
                ->with(['product:id,name,sku', 'user:id,name'])
                ->each(function (InventoryTransaction $transaction) use ($output): void {
                    fputcsv($output, [
                        $transaction->created_at->toDateTimeString(),
                        $transaction->product?->name,
                        $transaction->product?->sku,
                        $transaction->type,
                        $transaction->signed_quantity,
                        $transaction->reference_number,
                        $transaction->user?->name,
                        $transaction->notes,
                    ]);
                });

            fclose($output);
        }, 'inventory-transactions.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredTransactions(array $filters)
    {
        return InventoryTransaction::query()
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->latest();
    }
}
