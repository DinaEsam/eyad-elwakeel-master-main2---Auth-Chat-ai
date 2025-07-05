<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'password', 'specialization', 'experience', 'address'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
