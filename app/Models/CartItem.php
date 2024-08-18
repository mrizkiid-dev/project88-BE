<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_item';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $casts = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];
    public $incrementing = true;

    protected $fillable = [
        'session_id',
        'product_id',
        'qty',
        'image_url'
    ];

    public function shoppingSession(): BelongsTo {
        return $this->belongsTo(ShoppingSession::class, 'session_id', 'id');
    }  

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
