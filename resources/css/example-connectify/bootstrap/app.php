<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\User;
use App\Http\Middleware\ProjectVerification;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'users' => User::class,
            'project_verification' => ProjectVerification::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
