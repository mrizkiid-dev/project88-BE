<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingSession extends Model
{
    use HasFactory;
    protected $table = "shopping_session";
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
        'total_payment',
        'sub_total',
        'is_done',
        'user_uuid',
    ];

    public function userSupabase(): BelongsTo {
        return $this->belongsTo(UserSupabase::class, 'user_uuid', 'uuid');
    }

    public function cartItem(): HasMany {
        return $this->hasMany(CartItem::class, 'session_id', 'id');
    }

    public function order(): HasMany {
        return $this->hasMany(ShoppingSession::class, 'shopping_session_id', 'id');
    }
}
