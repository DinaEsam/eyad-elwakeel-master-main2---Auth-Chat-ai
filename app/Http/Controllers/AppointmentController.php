<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function availableAppointments(Request $request, $doctor_id)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $doctor = User::where('id', $doctor_id)->where('role', 'doctor')->first();
        if (!$doctor) {
            return response()->json(['message' => 'الدكتور غير موجود.'], 404);
        }

        $query = Appointment::where('doctor_id', $doctor_id)
                            ->where('is_booked', false);

        if ($request->has('date')) {
            $request->validate([
                'date' => 'date',
            ]);
            $query->where('date', $request->date);
        }

        $appointments = $query->orderBy('date')->orderBy('start_time')->get();

        if ($appointments->isEmpty()) {
            return response()->json([
                'message' => 'لا توجد مواعيد متاحة للدكتور ' . $doctor->name . ' حالياً.'
            ], 200);
        }

        // 🧾 رجع المواعيد مع اسم الدكتور
        $formatted = $appointments->map(function ($appointment) use ($doctor) {
            return [
                'id' => $appointment->id,
                'date' => $appointment->date,
                'start_time' => $appointment->start_time,
                'end_time' => $appointment->end_time,
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'phone' => $doctor->phone ?? null,
                ],
            ];
        });

        return response()->json($formatted);
    }

public function reserve(Request $request, $appointment_id)
{
    $request->validate([
        'patient_name' => 'required|string',
        'phone_number' => 'required|string',
    ]);

    $appointment = Appointment::find($appointment_id);

    if (!$appointment) {
        return response()->json(['message' => 'الموعد غير موجود.'], 404);
    }

    if ($appointment->is_booked) {
        return response()->json(['message' => 'الموعد محجوز بالفعل.'], 400);
    }

    Reservation::create([
        'appointment_id' => $appointment_id,
        'patient_name' => $request->patient_name,
        'phone_number' => $request->phone_number,
    ]);

    $appointment->update(['is_booked' => true]);

    return response()->json(['message' => 'تم الحجز بنجاح.']);
}

}
