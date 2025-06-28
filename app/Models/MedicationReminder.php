<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
  use HasFactory;

    protected $fillable = [
      
        'medicine_name',
        'dose_count',
        'period',
        'time',
    ];
}
