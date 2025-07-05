<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicationReminder;
use App\Models\WaterReminder;
use App\Models\DialysisReminder;

class ReminderController extends Controller
{
    // حفظ تذكير دواء
    public function storeMedication(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        // التحقق من القيم
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dose_count' => 'required|numeric|min:1',
            'period' => 'required|string|max:255',
            'time' => 'required|date_format:H:i',
        ], [
            'medicine_name.required' => 'برجاء إدخال اسم الدواء',
            'dose_count.required' => 'برجاء إدخال عدد الجرعات',
            'period.required' => 'برجاء إدخال المدة',
            'time.required' => 'برجاء إدخال الوقت',
            'time.date_format' => 'صيغة الوقت يجب أن تكون HH:MM (مثال: 08:00)',
        ]);

        MedicationReminder::create([
            'user_id' => Auth::id(),
            'medicine_name' => $request->medicine_name,
            'dose_count' => $request->dose_count,
            'period' => $request->period,
            'time' => $request->time,
        ]);

        return response()->json(['message' => 'تم حفظ تذكير الدواء بنجاح']);
    }

    // حفظ تذكير مياه
    public function storeWater(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        // التحقق من القيم
        $request->validate([
            'type' => 'required|in:male,female',
            'wake_up_time' => 'required|date_format:H:i',
            'sleep_time' => 'required|date_format:H:i',
            'reminder_every' => 'required|numeric|min:1',
        ], [
            'type.required' => 'برجاء اختيار النوع (ذكر أو أنثى)',
            'type.in' => 'القيمة يجب أن تكون male أو female فقط',
            'wake_up_time.required' => 'برجاء إدخال وقت الاستيقاظ',
            'wake_up_time.date_format' => 'صيغة الوقت يجب أن تكون HH:MM',
            'sleep_time.required' => 'برجاء إدخال وقت النوم',
            'sleep_time.date_format' => 'صيغة الوقت يجب أن تكون HH:MM',
            'reminder_every.required' => 'برجاء إدخال عدد مرات التذكير',
        ]);

        WaterReminder::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'wake_up_time' => $request->wake_up_time,
            'sleep_time' => $request->sleep_time,
            'reminder_every' => $request->reminder_every,
        ]);

        return response()->json(['message' => 'تم حفظ تذكير المياه بنجاح']);
    }

    // حفظ تذكير جلسات غسيل
    public function storeDialysis(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        // التحقق من القيم
        $request->validate([
            'sessions_per_week' => 'required|numeric|min:1|max:7',
            'start_date' => 'required|date',
            'session_time' => 'required|date_format:H:i',
        ], [
            'sessions_per_week.required' => 'برجاء إدخال عدد الجلسات في الأسبوع',
            'sessions_per_week.numeric' => 'يجب أن يكون عدد الجلسات رقمًا',
            'start_date.required' => 'برجاء إدخال تاريخ البدء',
            'start_date.date' => 'تاريخ البدء غير صالح',
            'session_time.required' => 'برجاء إدخال وقت الجلسة',
            'session_time.date_format' => 'صيغة الوقت يجب أن تكون HH:MM',
        ]);

        DialysisReminder::create([
            'user_id' => Auth::id(),
            'sessions_per_week' => $request->sessions_per_week,
            'start_date' => $request->start_date,
            'session_time' => $request->session_time,
        ]);

        return response()->json(['message' => 'تم حفظ تذكير الغسيل بنجاح']);
    }
    public function getReminders()
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }
        $user = Auth::guard('sanctum')->user();
        $medications = MedicationReminder::where('user_id', $user->id)->get();
        $waters = WaterReminder::where('user_id', $user->id)->get();
        $dialysis = DialysisReminder::where('user_id', $user->id)->get();

        return response()->json([
            'medications' => $medications,
            'waters' => $waters,
            'dialysis' => $dialysis,
        ]);
    }
    ##########
    public function deleteMedication($id)
    {
          if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }


        $reminder = MedicationReminder::where('user_id', Auth::id())->find($id);

        
        if (!$reminder) {
            return response()->json(['message' => 'تذكير الدواء غير موجود'], 404);
        }
        $reminder->delete();

        return response()->json(['message' => 'تم حذف تذكير الدواء بنجاح']);
    }
    ##########
    public function deleteWater($id)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $reminder = WaterReminder::where('user_id', Auth::id())->find($id);

        if (!$reminder) {
            return response()->json(['message' => 'تذكير المياه غير موجود'], 404);
        }

        $reminder->delete();

        return response()->json(['message' => 'تم حذف تذكير المياه بنجاح']);
    }
    ##########
    public function deleteDialysis($id)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $reminder = DialysisReminder::where('user_id', Auth::id())->find($id);

        if (!$reminder) {
            return response()->json(['message' => 'تذكير الغسيل غير موجود'], 404);
        }

        $reminder->delete();

        return response()->json(['message' => 'تم حذف تذكير الغسيل بنجاح']);
    }
}
