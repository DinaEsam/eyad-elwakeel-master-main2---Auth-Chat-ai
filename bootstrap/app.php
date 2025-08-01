<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ تسجيل جميع Middleware دفعة واحدة
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'patient' => \App\Http\Middleware\IsPatient::class,
            'doctor' => \App\Http\Middleware\IsDoctor::class,
            'admin_or_doctor' => \App\Http\Middleware\AdminOrDoctorMiddleware::class,

        ]);

        // ✅ تأكد من إضافة Middleware الخاص بـ Laravel Sanctum
        $middleware->append(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
