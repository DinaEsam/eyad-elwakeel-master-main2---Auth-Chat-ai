<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialysisReminder extends Model
{
 use HasFactory;

    protected $fillable = [
          'user_id',
        'sessions_per_week',
        'start_date',
        'session_time',
    ];}
