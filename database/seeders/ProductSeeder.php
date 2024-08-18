<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $category = ProductCategory::where('name', 'category-1')->first();
            $products = [];
            for ($i=0; $i < 100; $i++) { 
                $data = [
                    'category_id' => $category->id,
                    'SKU' => 'SKU-SEED-TEST-'.$i,
                    'name' => 'product name '.$i,
                    'desc' => 'product desc '.$i,
                    'price' => 10000 + $i,
                    'discount' => 10 + $i,
                    'qty' => 100,
                    'sell_out' => 10,
                    'weight' => 100
                ];
                array_push($products, $data);
            }
            DB::table('product')->insert($products);

            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('ProductSeeder error = '.$e->getMessage());
            DB::rollBack();
        }
    }
}
