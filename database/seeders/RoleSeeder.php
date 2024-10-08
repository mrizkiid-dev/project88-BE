<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        try {
            DB::beginTransaction();
            Role::create([
                'role_name' => 'admin',
            ]);

            Role::create([
                'role_name' => 'user',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('RoleSeeder error = '.$e->getMessage());
            DB::rollBack();
        }
    }
}
