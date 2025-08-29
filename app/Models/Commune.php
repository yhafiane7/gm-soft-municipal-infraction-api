<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $table = 'commune';
    protected $fillable=[
        'pachalik-circon',
        'caidat',
        'nom',
        'latitude',
        'longitude'
    ];
}