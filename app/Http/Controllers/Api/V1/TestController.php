<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function get() {
        try {
            $data = Auth::user();
            return response()->json(['success' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e]);
        }
        
    }
}
