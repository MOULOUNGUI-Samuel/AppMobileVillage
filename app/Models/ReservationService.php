<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReservationService extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['reservation_id', 'service_id', 'quantite', 'prix_unitaire'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}

