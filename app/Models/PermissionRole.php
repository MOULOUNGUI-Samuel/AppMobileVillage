<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'permissions_roles';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // UUIDs non nécessaires ici si pas de clé primaire autre que composée
        });
    }

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
