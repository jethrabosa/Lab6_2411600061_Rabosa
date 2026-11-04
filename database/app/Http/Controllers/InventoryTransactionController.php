<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class InventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        if (! empty($validated['from'])) {
            $query->whereDate('created_at', '>=', $validated['from']);
        }

        if (! empty($validated['to'])) {
            $query->whereDate('created_at', '<=', $validated['to']);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'products'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:stock_in,stock_out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['user_id'] = Auth::id();

        DB::transaction(function () use ($validated): void {
            $product = Product::query()->lockForUpdate()->findOrFail($validated['product_id']);

            if ($validated['type'] === 'stock_out' && $validated['quantity'] > $product->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Not enough stock to complete this stock-out transaction.',
                ]);
            }

            $product->transactions()->create($validated);
        });

        return Redirect::route('transactions.index')->with('success', 'Inventory transaction created successfully.');
    }

    public function show(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->load(['product', 'user']);

        return view('transactions.show', compact('inventoryTransaction'));
    }

    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        abort_unless(Auth::user()?->is_admin, 403);

        DB::transaction(function () use ($inventoryTransaction): void {
            $product = Product::query()->lockForUpdate()->findOrFail($inventoryTransaction->product_id);
            $change = match ($inventoryTransaction->type) {
                'stock_in' => (int) $inventoryTransaction->quantity,
                'stock_out' => -(int) $inventoryTransaction->quantity,
                'adjustment' => (int) $inventoryTransaction->quantity,
            };
            $reversedQuantity = (int) $product->quantity - $change;

            if ($reversedQuantity < 0) {
                throw ValidationException::withMessages([
                    'transaction' => 'This transaction cannot be deleted because reversing it would make stock negative.',
                ]);
            }

            $product->update(['quantity' => $reversedQuantity]);
            $inventoryTransaction->delete();
        });

        return Redirect::route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function adjustment()
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.adjustment', compact('products'));
    }

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'physical_count' => ['required', 'integer', 'min:0'],
            'reason' => ['required', 'in:physical_count,damaged,loss,system_error,other'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated): void {
            $product = Product::query()->lockForUpdate()->findOrFail($validated['product_id']);
            $difference = (int) $validated['physical_count'] - (int) $product->quantity;

            if ($difference === 0) {
                throw ValidationException::withMessages([
                    'physical_count' => 'The physical count matches the system quantity; no adjustment is needed.',
                ]);
            }

            $notes = $validated['notes'] ?? null;
            $product->transactions()->create([
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'quantity' => $difference,
                'reference_number' => 'ADJ-'.now()->format('YmdHis'),
                'notes' => 'Reason: '.str_replace('_', ' ', $validated['reason'])
                    .($notes ? '. '.$notes : ''),
            ]);
        });

        return Redirect::route('transactions.index')->with('success', 'Stock adjustment recorded successfully.');
    }
}
