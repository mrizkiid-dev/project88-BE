<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            ProductCategory::create([
                'name' => 'category-1',
                'desc' => 'desc-category-1'
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('ProductCategorySeeder error = ',$e->getMessage());
            DB::rollBack();
        }
    }
}
