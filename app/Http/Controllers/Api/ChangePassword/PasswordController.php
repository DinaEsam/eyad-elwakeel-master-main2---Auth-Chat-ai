<?php

namespace App\Http\Controllers\Api\ChangePassword;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PasswordController extends Controller
{
    //$user = User::find(Auth::id());
    // Change Password
    public function  changePassword(Request $request)
{
    // Get the current authenticated user
    $user = User::find(Auth::id());

    // Check if the user exists
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // Validate current and new passwords
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
        'new_password_confirmation' => 'required|same:new_password',
    ]);

    // Check if the current password matches
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['error' => 'Current password is incorrect'], 400);
    }
    // Ensure the new password is different from the old password
    if (Hash::check($request->new_password, $user->password)) {
        return response()->json(['error' => 'New password cannot be the same as the current password'], 400);
    }

    // Update the password using save()
    try {
        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password has been changed successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to change password: ' . $e->getMessage()], 500);
    }
}


}
