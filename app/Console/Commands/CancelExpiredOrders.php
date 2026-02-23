<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel orders that are unpaid after 24 hours';

    public function handle()
    {
        $expiredOrders = OrderModel::with('items.variant')
            ->where('payment_status', 'pending')
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();
        // $expiredOrders = OrderModel::where('payment_status', 'pending')
        //     ->where('created_at', '<=', Carbon::now()->subMinutes(1))
        //     ->get();

        foreach ($expiredOrders as $order) {

            DB::transaction(function () use ($order) {

                // 🔥 KEMBALIKAN STOCK
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }

                // 🔥 UPDATE STATUS
                $order->update([
                    'status' => 'cancel',
                ]);
            });

            $this->info("Order #{$order->id} cancelled.");
        }

        return Command::SUCCESS;
    }
}
