<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = $this->productCategory;
        $product_images = $this->productImage;
        return [
            'id' => $this->id,
            'sku' => $this->SKU,
            'name' => $this->name,
            'description' => $this->desc,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->desc
            ],
            'product_image' => $product_images->map(function($product_image) {
                return [
                    'id' => $product_image->id,
                    'image_url' => $product_image->image_url
                ];
            }),
            'price' => $this->price,
            'discount' => $this->discount,
            'qty' => $this->qty,
            'weight' => $this->weight,
            'sell_out' => $this->sell_out,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at
        ];
    }
}
