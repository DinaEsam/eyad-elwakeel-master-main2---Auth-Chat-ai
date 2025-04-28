<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
     // تسجيل مستخدم جديد
//      public function register(Request $request)
// {
//     // التحقق من أن البريد الإلكتروني أو الرقم القومي غير مسجلين بالفعل
//     if (User::where('email', $request->email)->exists()) {
//         return response()->json([
//             'message' => 'البريد الإلكتروني مسجل مسبقًا'
//         ], 400);
//     }

//     if (User::where('national_id', $request->national_id)->exists()) {
//         return response()->json([
//             'message' => 'الرقم القومي مسجل مسبقًا'
//         ], 400);
//     }

//     // التحقق من صحة البيانات المدخلة
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:6|confirmed',
//         'password_confirmation' => 'required|string|min:6',
//         'national_id' => 'nullable|string|unique:users',
//         'phone' => 'nullable|string|unique:users',
//         'weight' => 'nullable|numeric',
//         'height' => 'nullable|numeric',
//         'has_chronic_diseases' => 'boolean',
//         'is_following_with_doctor' => 'boolean',
//         'diagnosis_date' => 'nullable|date',
//         'disease_stage' => 'nullable|string',
//         'latitude' => 'nullable|numeric',
//         'longitude' => 'nullable|numeric',
//     ]);

//     // إنشاء المستخدم الجديد
//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//         'role' => 'patient',
//         'national_id' => $request->national_id,
//         'phone' => $request->phone,
//         'weight' => $request->weight,
//         'height' => $request->height,
//         'has_chronic_diseases' => $request->has_chronic_diseases,
//         'is_following_with_doctor' => $request->is_following_with_doctor,
//         'diagnosis_date' => $request->diagnosis_date,
//         'disease_stage' => $request->disease_stage,
//         'latitude' => $request->latitude,
//         'longitude' => $request->longitude,
//     ]);

//     // إنشاء التوكن الخاص بالمستخدم
//     $token = $user->createToken('auth_token')->plainTextToken;

//     // إرجاع الاستجابة
//     return response()->json([
//         'message' => 'تم تسجيل المستخدم بنجاح',
//         'user' => $user,
//         'token' => $token,
//     ], 201);
// }

public function register(Request $request)
{
    $data = $request->all(); // دي بتجمع البيانات من البودي والـ query مع بعض

    $validator = Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'national_id' => 'nullable|string|unique:users',
        'phone' => 'nullable|string|unique:users',
        'weight' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        'has_chronic_diseases' => 'nullable|boolean',
        'is_following_with_doctor' => 'nullable|boolean',
        'diagnosis_date' => 'nullable|date|before_or_equal:today',
        'disease_stage' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Registration failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $validatedData = $validator->validated();

    try {
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'patient',
            'national_id' => $validatedData['national_id'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
            'weight' => $validatedData['weight'] ?? null,
            'height' => $validatedData['height'] ?? null,
            'has_chronic_diseases' => $validatedData['has_chronic_diseases'] ?? null,
            'is_following_with_doctor' => $validatedData['is_following_with_doctor'] ?? null,
            'diagnosis_date' => $validatedData['diagnosis_date'] ?? null,
            'disease_stage' => $validatedData['disease_stage'] ?? null,
            'latitude' => $validatedData['latitude'] ?? null,
            'longitude' => $validatedData['longitude'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}

      // تسجيل الدخول
//      public function login(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (!$user) {
//         throw ValidationException::withMessages([
//             'email' => ['The provided email does not exist in our records.'],
//         ])->status(401);
//     }

//     if (!Hash::check($request->password, $user->password)) {
//         throw ValidationException::withMessages([
//             'password' => ['The provided password is incorrect.'],
//         ])->status(401);
//     }

//     $token = $user->createToken('auth_token')->plainTextToken;

//     return response()->json([
//         'message' => 'User logged in successfully',
//         'token' => $token,
//         'user' => $user
//     ], 200);
// }
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password))
    {
        return response()->json([
            'message' => 'Invalid email or password.',
        ], 401);
    }
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'User logged in successfully',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'token' => $token,
    ], 200);
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

