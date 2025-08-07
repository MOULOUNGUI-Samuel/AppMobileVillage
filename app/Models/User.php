<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
    use HasFactory, Notifiable, HasUuids, SoftDeletes; // Suppression logique activée

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['nom', 'email','role_id', 'password', 'role_user', 'entreprise_id'];
    
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relation avec l'entreprise.
     */
    public function entreprise() {
        return $this->belongsTo(Entreprise::class);
    }
    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }
    /**
     * Relation avec les réservations.
     */
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permissions_users');
    }
}
