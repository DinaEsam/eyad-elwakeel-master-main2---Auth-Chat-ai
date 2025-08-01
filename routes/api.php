<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\Comments\CommentsController;
use App\Http\Controllers\Api\ChangePassword\PasswordController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FastApiController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\StatsController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    //chat
    Route::post('/chat/start', [ChatController::class, 'startChat']); // Start chat
    Route::post('/chat/send', [ChatController::class, 'sendMessage']); // Send message
    Route::get('/chat/{chat_id}/messages', [ChatController::class, 'getMessages']); // Get messages
    Route::get('/chats', [ChatController::class, 'listChats']); // List chats
    Route::put('/chat/message/{message_id}', [ChatController::class, 'editMessage']); // Edit message
    Route::delete('/chat/message/{message_id}', [ChatController::class, 'deleteMessage']); // Delete message
    Route::post('/chat/message/{message_id}/react', [ChatController::class, 'reactToMessage']); //react
    Route::get('/search-users', [ChatController::class, 'searchUsers']);
    // api related with doctor
    Route::post('/doctors', [DoctorController::class, 'store'])->middleware('admin');
    Route::get('/all-doctors', [DoctorController::class, 'index'])->middleware('admin');
    Route::delete('/doctors/{id}', [DoctorController::class,'destroy'])->middleware('admin');
    Route::put('/doctors/{id}', [DoctorController::class,'update'])->middleware('admin_or_doctor');

    //api related patient
    Route::get('/all-patients', [PatientController::class, 'index'])->middleware('admin');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->middleware('admin');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->middleware('patient');

    // change password
    Route::post('/change-password', [PasswordController::class, 'changePassword']);

    // Knowing the number of visits and the number of doctors and patients
    Route::get('/stats', [StatsController::class, 'index'])->middleware('admin');
    //api related Complaints and suggestions
    Route::get('/comments', [CommentsController::class, 'index'])->middleware('admin');
    Route::get('/comments/{id}', [CommentsController::class, 'show'])->middleware('admin');
    Route::delete('/comments/{id}', [CommentsController::class, 'destroy'])->middleware('admin');
    // Notifications
    Route::get('/reminders', [ReminderController::class, 'getReminders']);
    Route::post('/medication', [ReminderController::class, 'storeMedication']);
    Route::post('/water', [ReminderController::class, 'storeWater']);
    Route::post('/dialysis', [ReminderController::class, 'storeDialysis']);
    Route::delete('/medication/delete/{id}', [ReminderController::class, 'deleteMedication']);
    Route::delete('/water/delete/{id}', [ReminderController::class, 'deleteWater']);
    Route::delete('/dialysis/delete/{id}', [ReminderController::class, 'deleteDialysis']);
});
#ai
Route::post('/send-image', [FastApiController::class, 'sendImage']);
//api related Complaints and suggestions
Route::post('/comments', [CommentsController::class, 'store']);

//appointments
Route::get('/appointments/available/{doctor_id}', [AppointmentController::class, 'availableAppointments']);
Route::post('/appointments/reserve/{appointment_id}', [AppointmentController::class, 'reserve']);
//email
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [ResetPasswordController::class, 'verifyResetCode']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

// Check unique Fields
Route::post('/check-unique-fields', [AuthController::class, 'checkUniqueFields']);

//Anyone can leave a comment
Route::post('/comments', [CommentsController::class, 'store']);
