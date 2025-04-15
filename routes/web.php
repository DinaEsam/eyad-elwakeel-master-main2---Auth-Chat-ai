<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/doctors', function () {
    return view('doctor');
});
Route::get('/all-doctors', [DoctorController::class, 'index']);

Route::get('/chat', function () {
    return view('chat');
});