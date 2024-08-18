<?php

namespace App\Models;

use App\Enums\StatusOrderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderConfirmation extends Model
{
    use HasFactory;
    protected $table = 'order_confirmation';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    public function getStatusAttribute($value)
    {
        return StatusOrderEnum::from($value);
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $casts = [
        // 'status' => StatusOrderEnum::class,
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'is_confirmed',
        'status'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
