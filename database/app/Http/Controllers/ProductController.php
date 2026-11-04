<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $initialQuantity = $validated['quantity'];
        $validated['quantity'] = 0;

        DB::transaction(function () use ($validated, $initialQuantity): void {
            $product = Product::create($validated);

            if ($initialQuantity > 0) {
                $product->transactions()->create([
                    'user_id' => Auth::id(),
                    'type' => 'stock_in',
                    'quantity' => $initialQuantity,
                    'reference_number' => 'OPENING-'.$product->sku,
                    'notes' => 'Opening stock',
                ]);
            }
        });

        return Redirect::route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Request $request, Product $product)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $allTransactions = $product->transactions()->with('user')->latest()->get();
        $balance = (int) $product->quantity;

        if (! empty($validated['to'])) {
            foreach ($allTransactions as $transaction) {
                if ($transaction->created_at->toDateString() > $validated['to']) {
                    $balance -= $this->transactionChange($transaction);
                }
            }
        }

        $transactions = $allTransactions
            ->filter(fn ($transaction) => empty($validated['from']) || $transaction->created_at->toDateString() >= $validated['from'])
            ->filter(fn ($transaction) => empty($validated['to']) || $transaction->created_at->toDateString() <= $validated['to'])
            ->values();

        foreach ($transactions as $transaction) {
            $transaction->running_balance = $balance;
            $balance -= $this->transactionChange($transaction);
        }

        return view('products.show', compact('product', 'transactions', 'validated'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $product->update($validated);

        return Redirect::route('products.show', $product)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->transactions()->exists()) {
            return Redirect::route('products.show', $product)
                ->withErrors(['product' => 'Products with transaction history cannot be deleted.']);
        }

        $product->delete();

        return Redirect::route('products.index')->with('success', 'Product deleted successfully.');
    }

    private function transactionChange($transaction): int
    {
        return match ($transaction->type) {
            'stock_in' => (int) $transaction->quantity,
            'stock_out' => -(int) $transaction->quantity,
            'adjustment' => (int) $transaction->quantity,
        };
    }
}
