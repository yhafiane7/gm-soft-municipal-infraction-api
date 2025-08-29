<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infraction extends Model
{
    use HasFactory;
    protected $table = 'infraction';
    protected $fillable = [
        'nom',
        'date',
        'adresse',
        'commune_id',
        'violant_id',
        'agent_id',
        'categorie_id',
        'latitude',
        'longitude',
    ];
}
