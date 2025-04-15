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

         $token = $user->createToken('auth_token')->plainTextToken;

         return response()->json([
             'message' => 'User registered successfully',
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
