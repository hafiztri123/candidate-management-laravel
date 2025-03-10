<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'skills',
        'experience',
        'current_position',
        'education',
        'status'
    ];
}
