<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id', 'date', 'start_time', 'end_time', 'is_booked',
    ];

    // ✅ العلاقة مع جدول users (الدكتور)
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}

