<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['nom', 'description', 'tarif', 'entreprise_id', 'statut'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
