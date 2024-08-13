<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $table = "order";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    
    protected $casts = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    protected $fillable = [
        'shopping_session_id',
        'name_receiver',
        'detail_address',
        'city_id',
        'city',
        'province_id',
        'province',
        'midtrans_id',
        'midtrans_token',
        'total_payment',
        'shipping_price',
        'sub_total',
        'status'
    ];

    public function shoppingSession(): BelongsTo {
        return $this->belongsTo(ShoppingSession::class, 'shopping_session_id', 'id');
    }

    public function orderConfirmation(): HasOne {
        return $this->hasOne(OrderConfirmation::class, 'order_id', 'id');
    }

    public function orderItem(): HasMany {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
