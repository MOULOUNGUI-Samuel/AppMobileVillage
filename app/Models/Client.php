<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Client extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'solde','ancien_solde', 'adresse','observation'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
    public function entreprise() {
        return $this->belongsTo(Entreprise::class);
    }
}
