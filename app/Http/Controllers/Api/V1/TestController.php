<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function get() {
        try {
            $order = Order::find(29);
            
            $data = [
                'id' => $order->id,
                'name' => $order->name_receiver,
                'order_item' => $order->orderItem
            ];
            return response()->json(['success' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e]);
        }
        
    }
}
