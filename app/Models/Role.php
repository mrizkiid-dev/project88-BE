<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $casts = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    protected $fillable = [
        'role_name'
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            table: 'user_role',
            foreignPivotKey: 'role_id',
            relatedPivotKey: 'users_id'
        );
    }
}
