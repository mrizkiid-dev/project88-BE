<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $orders = Order::all();
            foreach ($orders as $order) {
                $products = Product::limit(5)->get();
                $items = [];
                foreach ($products as $product) {
                    $imageUrl = $product->productImage()->first()->image_url;
                    $item = [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => 2,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $imageUrl
                    ];
                }
                OrderItem::insert($item);
            }
        } catch (\Throwable $e) {
            Log::debug('OrderItemSeeder error = '.$e->getMessage());
        }
    }
}
