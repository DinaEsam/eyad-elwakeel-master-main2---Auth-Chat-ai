<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FastApiService;
use Illuminate\Support\Facades\Auth;

class FastApiController extends Controller
{
    protected $fastApiService;

    public function __construct(FastApiService $fastApiService)
    {
        $this->fastApiService = $fastApiService;
    }

    public function sendImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'الصورة غير موجودة'], 400);
        }

        $image = $request->file('file');
        $imagePath = $image->store('users', 'public');

        $response = $this->fastApiService->sendImage($image);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'يجب تسجيل الدخول أولًا'], 403);
        }

        User::where('id', $user->id)->update([
            'profile_image' => $imagePath,
            'ai_response_text' => $response,
        ]);

        return response()->json([
            'image_url' => asset("storage/{$imagePath}"),
            'ai_response' => $response,
        ]);
    }
}
