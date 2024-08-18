<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Product;
use App\Models\ShoppingSession;
use App\Models\User;
use App\Models\UserSupabase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShoppingSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $userNoAdmin = User::whereHas('role', function($query) {
                $query->where('role_name', RoleEnum::USER);
            })->get();

            foreach ($userNoAdmin as $item) {
                ShoppingSession::create([
                    'user_uuid' => $item->uuid,
                    'total_payment' => 0,
                    'sub_total' => 0,
                    'is_done' => 0
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('Shopping Session Error = '.$e->getMessage());
            DB::rollBack();
            # code...
        }
        
    }

    public function runWithUserUuid($uuid)
    {
        try {
            DB::beginTransaction();
            // $user = UserSupabase::where('name', 'user1')->first();
            ShoppingSession::create([
                'user_uuid' => $uuid,
                'total_payment' => 0,
                'sub_total' => 0,
                'is_done' => 0
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            # code...
        }
    }
}
