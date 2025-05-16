<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiService
{
    protected $fastApiUrl;

    public function __construct()
    {
        $this->fastApiUrl = 'https://graduationproject-production-8fc3.up.railway.app/upload-image/';
    }

    public function sendImage($image)
    {
        if (!$image) {
            Log::error(" No image received!");
            return ["error" => "No image received"];
        }
    
        Log::info(" Sending image: " . $image->getClientOriginalName());
    
        try {
            $response = Http::attach(
                'file',
                file_get_contents($image->getPathname()),
                $image->getClientOriginalName()
            )->post($this->fastApiUrl);
    
            $result = $response->json();
            Log::info(" FastAPI Response:", $result);
    
            return $result;
    
        } catch (\Exception $e) {
            Log::error(" Error in FastAPI request: " . $e->getMessage());
            return ["error" => "FastAPI request failed", "message" => $e->getMessage()];
        }
    }
}
