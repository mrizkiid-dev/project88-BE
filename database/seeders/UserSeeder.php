<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Brick\Math\Exception\DivisionByZeroException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('admin123')
            ]);
    
            $role = Role::where('role_name', 'admin')->first();
            $user->role()->attach($role->id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            echo 'Error: ' . $e->getMessage() . "\n";
            echo 'Error Code: ' . $e->getCode() . "\n";
            echo 'File: ' . $e->getFile() . "\n";
            echo 'Line: ' . $e->getLine() . "\n";
            echo 'Trace: ' . $e->getTraceAsString() . "\n";
            // throw n
        }
    }
}
