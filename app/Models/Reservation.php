<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Reservation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['client_id', 'user_id', 'salle_id',"ref_quitance", 'start_date', 'end_date','montant_total','montant_quitance','montant_payer','montant_reduction','statut_valider', 'description_rejet', 'statut','description'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
    public function caution()
    {
        return $this->hasOne(Caution::class);
    }
    public function reservationServices()
    {
        return $this->hasMany(ReservationService::class, 'reservation_id');
    }

    public function approbationEvenement()
    {
        return $this->hasOne(ApprobaEvenement::class);
    }
    
    /**
     * Obtenir les mouvements de caisse associés à la réservation.
     */
    public function mouvements()
    {
        return $this->hasMany(MouvementCaisse::class);
    }

}
