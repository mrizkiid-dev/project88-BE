<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = "product";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $casts = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];
    public $incrementing = true;

    protected $fillable = [
        'category_id',
        'SKU',
        'name',
        'desc',
        'price',
        'discount',
        'qty',
        'sell_out',
        'weight',
    ];

    public function productCategory(): BelongsTo {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function cartItem(): HasMany {
        return $this->hasMany(CartItem::class, 'product_id', 'id');
    }

    public function orderItem(): HasMany {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function productImage(): HasMany {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
