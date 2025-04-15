<?php

namespace App\Http\Controllers;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function sendResetCode(Request $request)
{
    if (!$request->has('email') || empty($request->email)) {
        return response()->json(['message' => 'Email is required.'], 400);
    }

    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['message' => 'Invalid email format.'], 400);
    }

    if (!User::where('email', $request->email)->exists()) {
        return response()->json(['message' => 'Email not found.'], 404);
    }
// 
    $code = random_int(100000, 999999);

    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $code, 'created_at' => now()]
    );

    Mail::to($request->email)->send(new OtpMail($code));

    return response()->json(['message' => 'Reset code sent to your email.'], 200);
}
////
public function verifyResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'token' => 'required'
    ]);

    $passwordReset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$passwordReset || now()->diffInMinutes($passwordReset->created_at) > 30) {
        return response()->json(['message' => 'Invalid or expired reset code.'], 400);
    }

    return response()->json(['message' => 'Reset code is valid.'], 200);
}
//////
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'token' => 'required',
        'password' => 'required|confirmed|min:8'
    ]);

    $passwordReset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$passwordReset || now()->diffInMinutes($passwordReset->created_at) > 30) {
        return response()->json(['message' => 'Invalid or expired reset code.'], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

// // حذف جميع الأكواد التي مر عليها أكثر من 30 دقيقة
// DB::table('password_resets')->where('created_at', '<', now()->subMinutes(30))->delete();

    return response()->json(['message' => 'Password has been reset successfully.'], 200);
}


}

