<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ShoppingSession;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserSupabase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $shoppingSession = ShoppingSession::all();
            foreach ($shoppingSession as $item) {
                $user = UserSupabase::where('uuid', $item->user_uuid)->first();
                $address = $user->userAddress;
                Order::create([
                    'shopping_session_id' => $item->id,
                    'name_receiver' => $user->name,
                    'detail_address' => $address->detail_address,
                    'city_id' => $address->city_id,
                    'city' => $address->city,
                    'province_id' => $address->province_id,
                    'province' => $address->province,
                    'detail_address' => $address->additional_address,
                    'midtrans_token' => 'Midtrans-Token-Example',
                    'midtrans_id' => 'midtrans-id-example',
                    'total_payment' => 100000,
                    'shipping_price' => 1000,
                    'sub_total' => 101000,
                    'status' => 'pending'
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('OrderSeeder error = '.$e->getMessage());
            DB::rollBack();
        }
    }
}
