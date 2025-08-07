<?php

namespace App\Models; // <-- Vérifie que cette ligne est présente

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'client_name', 'start_date', 'end_date', 'room_name', 'price'];

    public $incrementing = false; // Désactiver l'incrémentation
    protected $keyType = 'string'; // Définir l'UUID comme clé primaire

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid(); // Générer un UUID lors de la création
        });
    }
}
