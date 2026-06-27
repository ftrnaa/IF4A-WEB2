<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')
        ->where('created_at', '<', Carbon::now()->subDay())
        ->get();

    foreach ($expiredOrders as $order) {
        $order->status = 'cancelled';
        $order->save();
    }

    $this->info('Expired orders cancelled successfully.');
    }
}
