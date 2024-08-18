<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use App\Models\UserSupabase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $users = UserSupabase::all();
            $addresses = [];
            foreach ($users as $user) {
                $data = [
                    'user_uuid' => $user->uuid,
                    'whatsapp_number' => 89212223232,
                    'province_id' => 1,
                    'province' => 'province test',
                    'city_id' => 2,
                    'city' => 'city test',
                    'district' => 'district test',
                    'additional_address' => 'additional address test',
                    'postal_code' => 45363
                ];

                array_push($addresses, $data);
            }

            UserAddress::insert($addresses);
            DB::commit();
        } catch (\Throwable $e) {
            Log::info('userAddressSeeder error = '.$e->getMessage());
            DB::rollBack();
        }
    }
}
