<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MouvementCaisse extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'mouvements_caisses';
    protected $fillable = [
        'ref_mouvement',
        'caisse_id',
        'client_id',
        'user_id',
        'reservation_id',
        'caution_id',
        'nature_mouvement',
        'type_mouvement',
        'mode_paiement',
        'montant',
        'montant_base',
        'nouveau_montant',
        'sum_solde',
        'description',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
    }

    public function compteClient()
    {
        return $this->belongsTo(Client::class, 'compte_client_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function caution()
    {
        return $this->belongsTo(Caution::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
