<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;
    protected $table = 'decision';
    protected $fillable=[
        'date',
        'decisionprise',
        'infraction_id',
    ];
}
