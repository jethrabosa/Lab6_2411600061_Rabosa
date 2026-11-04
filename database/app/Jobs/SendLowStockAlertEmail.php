<?php

namespace App\Jobs;

use App\Models\Product;
use App\Notifications\LowStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendLowStockAlertEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<int, string>  $recipients
     */
    public function __construct(
        public Product $product,
        public array $recipients
    ) {
        $this->afterCommit();
    }

    public function handle(): void
    {
        foreach ($this->recipients as $email) {
            Notification::route('mail', $email)->notify(new LowStockNotification($this->product));
        }
    }
}
