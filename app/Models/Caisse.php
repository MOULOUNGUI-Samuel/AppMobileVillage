<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Caisse extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['entreprise_id','user_id','nom', 'solde','seuil_caisse', 'statut_principale','statut_caisse'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mouvements()
    {
        // Une caisse "a plusieurs" (hasMany) mouvements.
        // Assurez-vous que le nom du modèle 'MouvementCaisse' est correct.
        return $this->hasMany(MouvementCaisse::class);
    }
}
