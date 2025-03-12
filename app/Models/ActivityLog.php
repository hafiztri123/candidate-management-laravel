<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'subject_type',
        'subject_id',
        'action',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
