<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'iyzico/webhook',
            '/payment/callback',
            '/profile/wallet/callback',
            'billing/*',
            'api/*',
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'subscribed' => \App\Http\Middleware\CheckSubscription::class,
        ]);
        
        $middleware->web(append: [
             \App\Http\Middleware\CheckSystemMaintenance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log critical errors to database
        $exceptions->report(function (Throwable $e) {
            try {
                \App\Models\ErrorLog::create([
                    'message' => $e->getMessage(),
                    'level' => 'error', // Default to error level
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => substr($e->getTraceAsString(), 0, 5000), // Limit trace size
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'user_id' => auth()->id(),
                ]);
            } catch (\Exception $ex) {
                // Fail silently if DB logging fails
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Lütfen önce giriş yapın.',
                    'status' => 'unauthenticated'
                ], 401);
            }
        });
    })->create();
