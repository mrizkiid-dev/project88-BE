<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderConfirmation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderConfirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    //     $orders = DB::table('order')->get();
    //     foreach ($orders as $order) {
    //         # code...
    //         DB::table('order_confirmation')->insert()
    //     }
        $orders = Order::all();
        foreach ($orders as $order) {
            OrderConfirmation::create([
                'order_id' => $order->id,
                'is_confirmed' => 1
            ]);
        }
    }    
}
