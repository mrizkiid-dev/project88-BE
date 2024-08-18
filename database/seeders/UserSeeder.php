<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserSupabase;
use Brick\Math\Exception\DivisionByZeroException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            // admin
            $user = User::create([
                'name' => 'admin1',
                'email' => 'admin1@email.com',
                'password' => bcrypt('Admin1@12345')
            ]);
    
            $role = Role::where('role_name', 'admin')->first();
            $user->role()->attach($role->id);

            $uuidAdmin = Str::orderedUuid();
            UserSupabase::create([
                'uuid' => $uuidAdmin,
                'name' => $user->name,
                'email' => $user->email
            ]);

            for ($i=1; $i < 5; $i++) { 
                $uuidUser = Str::orderedUuid();

                //user
                $user = User::create([
                    'name' => 'user'.$i,
                    'email' => 'user'.$i.'@email.com',
                    'password' => bcrypt('User'.$i.'@12345'),
                    'uuid' => $uuidUser
                ]);
        
                $role = Role::where('role_name', 'user')->first();
                $user->role()->attach($role->id);

                UserSupabase::create([
                    'uuid' => $uuidUser,
                    'name' => $user->name,
                    'email' => $user->email
                ]);
                DB::commit();
            }

        } catch (\Throwable $e) {
            Log::debug('UserSeeder error = '.$e->getMessage());
            DB::rollBack();
            // echo 'Error: ' . $e->getMessage() . '\n';
            // echo 'Error Code: ' . $e->getCode() . '\n';
            // echo 'File: ' . $e->getFile() . '\n';
            // echo 'Line: ' . $e->getLine() . '\n';
            // echo 'Trace: ' . $e->getTraceAsString() . '\n';
        }
    }

    public static function admin(): void
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('Admin@12345')
            ]);
    
            $role = Role::where('role_name', 'admin')->first();
            $user->role()->attach($role->id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            echo 'Error: ' . $e->getMessage() . '\n';
            echo 'Error Code: ' . $e->getCode() . '\n';
            echo 'File: ' . $e->getFile() . '\n';
            echo 'Line: ' . $e->getLine() . '\n';
            echo 'Trace: ' . $e->getTraceAsString() . '\n';
            // throw n
        }
    }

    public static function user(): void
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => 'user1',
                'email' => 'user1@email.com',
                'password' => bcrypt('User@12345')
            ]);
    
            $role = Role::where('role_name', 'admin')->first();
            $user->role()->attach($role->id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            echo 'Error: ' . $e->getMessage() . '\n';
            echo 'Error Code: ' . $e->getCode() . '\n';
            echo 'File: ' . $e->getFile() . '\n';
            echo 'Line: ' . $e->getLine() . '\n';
            echo 'Trace: ' . $e->getTraceAsString() . '\n';
            // throw n
        }
    }
}
