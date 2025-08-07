<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Entreprise extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['nom', 'adresse', 'telephone', 'email', 'statut'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
