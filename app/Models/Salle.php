<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Salle extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['nom', 'capacite', 'equipements', 'montant_base','caution','statut', 'entreprise_id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function entreprise() {
        return $this->belongsTo(Entreprise::class);
    }
}
