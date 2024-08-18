<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_address';
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
        'whatsapp_number',
        'district',
        'additional_address',
        'city_id',
        'city',
        'province_id',
        'province',
        'postal_code',
        'user_uuid'
    ];

    public function userSupabase(): BelongsTo {
        return $this->belongsTo(UserSupabase::class, 'user_uuid', 'uuid');
    }
}
