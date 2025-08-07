<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'permissions_users';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // UUIDs non nécessaires ici si pas de clé primaire autre que composée
        });
    }

    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
