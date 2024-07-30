<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HardDeleteExpiredProductsAndOrders extends Command
{
    protected $signature = 'expire:soft-delete';
    protected $description = 'Hard delete products and orders that have been deleted for more than 10 minutes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tenMinutesAgo = Carbon::now()->everyTenMinutes();

        // Get the product IDs of soft-deleted products older than 10 minutes
        $softDeletedProducts = Product::onlyTrashed()
            ->where('deleted_at', '<', $tenMinutesAgo)
            ->pluck('id');
       
        // Log::info(json_encode($softDeletedProducts));

        if ($softDeletedProducts->isEmpty()) {
            return;
        }

        // Get the orders related to these soft-deleted products
        $orders = Order::whereHas('items', function ($query) use ($softDeletedProducts) {
            $query->whereIn('product_id', $softDeletedProducts);
        })
        ->where('created_at', '<', $tenMinutesAgo)
        ->get();

        if ($orders->isEmpty()) {
            return;
        }

        // Delete the orders
        foreach ($orders as $order) {
            $order->delete();
        }

        $this->info('Deleted orders related to soft-deleted products older than 10 minutes.');
    }

}
