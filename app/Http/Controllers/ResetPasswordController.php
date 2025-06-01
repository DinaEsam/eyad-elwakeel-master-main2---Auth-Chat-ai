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
            return response()->json([
                'status' => false,
                'message' => 'Email is required.',
            ], 400);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email format.',
            ], 400);
        }

        if (!User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found.',
            ], 404);
        }

        $code = random_int(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
        );

        Mail::to($request->email)->send(new OtpMail($code));

        return response()->json([
            'status' => true,
            'message' => 'Reset code sent to your email.',
        ], 200);
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || now()->diffInMinutes($passwordReset->created_at) > 30) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired reset code.',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Reset code is valid.',
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || now()->diffInMinutes($passwordReset->created_at) > 30) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired reset code.'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // يمكنك تفعيل حذف الأكواد القديمة إن أردت
        // DB::table('password_resets')->where('created_at', '<', now()->subMinutes(30))->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password has been reset successfully.'
        ], 200);
    }
}

