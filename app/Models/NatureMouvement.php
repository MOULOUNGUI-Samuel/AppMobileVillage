<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NatureMouvement extends Model
{
    //
    use HasFactory, HasUuids;

    protected $fillable = ['entreprise_id','nom', 'acteur','type', 'statut'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
