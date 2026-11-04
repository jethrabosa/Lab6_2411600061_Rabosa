<?php

namespace App\Observers;

use App\Jobs\SendLowStockAlertEmail;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $inventoryTransaction): void
    {
        $product = $inventoryTransaction->product;

        if (! $product) {
            return;
        }

        $previousQuantity = (int) $product->quantity;
        $change = match ($inventoryTransaction->type) {
            'stock_in' => (int) $inventoryTransaction->quantity,
            'stock_out' => -(int) $inventoryTransaction->quantity,
            'adjustment' => (int) $inventoryTransaction->quantity,
            default => throw new \UnexpectedValueException('Unsupported inventory transaction type.'),
        };
        $newQuantity = $previousQuantity + $change;

        if ($newQuantity < 0) {
            throw ValidationException::withMessages([
                'quantity' => 'This transaction would reduce inventory below zero.',
            ]);
        }

        $product->quantity = $newQuantity;
        $product->save();

        if ($previousQuantity > $product->reorder_level && $product->isLowStock()) {
            $admins = User::query()->where('is_admin', true)->whereNotNull('email_verified_at')->get();
            Notification::send($admins, new LowStockNotification($product));
            if ($admins->isNotEmpty()) {
                SendLowStockAlertEmail::dispatch($product, $admins->pluck('email')->all());
            }
        }
    }
}
