<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
     // تسجيل مستخدم جديد
     public function register(Request $request)
{
    // التحقق من أن البريد الإلكتروني أو الرقم القومي غير مسجلين بالفعل
    if (User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'البريد الإلكتروني مسجل مسبقًا'
        ], 400);
    }

    if (User::where('national_id', $request->national_id)->exists()) {
        return response()->json([
            'message' => 'الرقم القومي مسجل مسبقًا'
        ], 400);
    }

    // التحقق من صحة البيانات المدخلة
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required|string|min:6',
        'national_id' => 'nullable|string|unique:users',
        'phone' => 'nullable|string|unique:users',
        'weight' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'has_chronic_diseases' => 'boolean',
        'is_following_with_doctor' => 'boolean',
        'diagnosis_date' => 'nullable|date',
        'disease_stage' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    // إنشاء المستخدم الجديد
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'patient',
        'national_id' => $request->national_id,
        'phone' => $request->phone,
        'weight' => $request->weight,
        'height' => $request->height,
        'has_chronic_diseases' => $request->has_chronic_diseases,
        'is_following_with_doctor' => $request->is_following_with_doctor,
        'diagnosis_date' => $request->diagnosis_date,
        'disease_stage' => $request->disease_stage,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    // إنشاء التوكن الخاص بالمستخدم
    $token = $user->createToken('auth_token')->plainTextToken;

    // إرجاع الاستجابة
    return response()->json([
        'message' => 'تم تسجيل المستخدم بنجاح',
        'user' => $user,
        'token' => $token,
    ], 201);
}

      // تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User Logged-in successfully',
            'user' => $user,
            'token' => $token,
            'role' => $user->role,
        ], 201);
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}

