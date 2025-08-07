<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = $model->id ?? (string) Str::uuid());
    }

    protected $fillable = ['libelle', 'description', 'groupe_id'];

    public function groupe()
    {
        return $this->belongsTo(GroupePermission::class, 'groupe_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions_users');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permissions_roles');
    }
}
