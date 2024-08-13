<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSupabase extends Model
{
    use HasFactory;
    protected $table = "user";
    protected $primaryKey = "uuid";
    protected $keyType = "string";
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $casts = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'uuid'
    ];

    public function userAddress(): HasOne {
        return $this->hasOne(UserAddress::class, 'user_uuid', 'uuid');
    }

    public function shoppingSession(): HasMany {
        return $this->hasMany(ShoppingSession::class, 'user_uuid', 'uuid');
    }
}
