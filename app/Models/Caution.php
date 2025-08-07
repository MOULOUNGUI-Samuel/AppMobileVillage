<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Caution extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['ref_caution', 'reservation_id', 'montant_caution','montant_rembourse', 'statut'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }
}

