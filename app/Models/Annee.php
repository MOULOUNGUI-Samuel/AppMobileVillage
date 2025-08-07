<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Annee extends Model {
    use HasFactory, HasUuids;

    protected $fillable = ['annee'];
    public $incrementing = false;
    protected $keyType = 'string';
}
