<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialysisReminder extends Model
{
 use HasFactory;

    protected $fillable = [
        'sessions_per_week',
        'start_date',
        'session_time',
    ];}
