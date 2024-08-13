<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    private $userId = null;
    public function __construct($id)
    {
        parent::__construct();
        $this->userId = $id;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $role = Role::where('role_name', 'admin')->first();
    }
}
