<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['appointment_id', 'patient_name', 'phone_number'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
