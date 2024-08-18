<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $products = Product::all();
            foreach ($products as $product) {
                $images = [];
                for ($i=1; $i < 6; $i++) { 
                    $image = [
                        'product_id' => $product->id,
                        'image_url' => 'http://127.0.0.1:8000/storage/products/'.$i.'.webp',
                        'name' => $i.'webp',
                        'path' => '/products/'.$i.'webp',
                    ];
                    array_push($images, $image);
                }
                DB::table('product_image')->insert($images);
            }
            DB::commit();
        } catch (\Throwable $e) {
            Log::debug('ProductImageSeeder error = ',$e->getMessage());
            DB::rollBack();
        }
    }
}
