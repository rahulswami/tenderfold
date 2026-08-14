<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'api/database/import',
            'chat/*',
            'chat/send',
            'chat/auto-trigger',
            'chat/history',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            // Log backend errors into internal admin notifications
            if (!($e instanceof \Illuminate\Auth\AuthenticationException) && !($e instanceof \Illuminate\Validation\ValidationException)) {
                \App\Models\AdminNotification::log(
                    'error',
                    'System Exception: ' . \Illuminate\Support\Str::limit($e->getMessage(), 120),
                    'File: ' . $e->getFile() . ' (Line ' . $e->getLine() . ')',
                    route('admin.tab', 'internal_notifications')
                );
            }
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
