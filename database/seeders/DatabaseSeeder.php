<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->resetDatabase();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ShoppingSessionSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductImageSeeder::class);
        $this->call(UserAddressSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderItemSeeder::class);
    }

    private function resetDatabase(){
        // DB::delete('delete from public.user_role');
        // DB::delete('delete from public.role');
        // DB::delete('delete from public.users');
        
        // DB::delete('delete from public.order');
        // DB::delete('delete from public.order_confirmation');
        // DB::delete('delete from public.order_item');
        // DB::delete('delete from public.product_image');
        // DB::delete('delete from public.product');
        // DB::delete('delete from public.product_category');
        // DB::delete('delete from public.shopping_session');
        // DB::delete('delete from public.user');
        // DB::delete('delete from public.user_address');
        // DB::delete('delete from public.users');

        $tableNames = array(
            "user_role",
            "role",
            "users",
            "order",
            "order_confirmation",
            "order_item",
            "product_image",
            "product",
            "product_category",
            "shopping_session",
            "user",
            "user_address",
            "users",
        );

        foreach ($tableNames as $tableName) {
            if (Schema::hasTable($tableName)) {
                // Delete all rows from the table
                DB::table($tableName)->delete();
            }
        }
    }
}
