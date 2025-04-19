<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FastApiService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;
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
           return response()->json(['error' => 'image not found'], 400);
       }
   
       $image = $request->file('file');
   
       // رفع إلى Imgur
       $imageData = base64_encode(file_get_contents($image->getRealPath()));
       $clientId = '30e9611acb57dcb'; // ← اكتبي الـ Client ID هنا
   
       $response = Http::withHeaders([
           'Authorization' => "Client-ID $clientId",
       ])->post('https://api.imgur.com/3/image', [
           'image' => $imageData,
       ]);
   
       if (!$response->successful()) {
           return response()->json(['error' => 'Failed to upload image to Imgur'], 500);
       }
   
       $uploadedFileUrl = $response['data']['link'];
   
       // إرسال الصورة لـ FastAPI
       $aiResponse = $this->fastApiService->sendImage($image);
   
       $user = Auth::user();
       if (!$user) {
           return response()->json(['error' => 'You must log in first'], 403);
       }
   
       User::where('id', $user->id)->update([
           'profile_image' => $uploadedFileUrl,
           'ai_response_text' => $aiResponse,
       ]);
   
       return response()->json([
           'image_url' => $uploadedFileUrl,
           'ai_response' => $aiResponse,
       ]);
   }
}
