<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violant extends Model
{
    use HasFactory;
    protected $table = 'violant';
    protected $fillable=['nom','prenom','cin'];
}
