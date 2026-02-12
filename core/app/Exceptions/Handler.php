<?php

namespace App\Exceptions;

use App\Mail\CriticalErrorNotification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $this->logErrorToDatabase($e);

            // Send email for critical errors in production
            if (app()->environment('production') && $this->isCritical($e)) {
                $this->notifyCriticalError($e);
            }
        });
    }

    /**
     * Log error to database for tracking
     */
    protected function logErrorToDatabase(Throwable $e): void
    {
        try {
            \App\Models\ErrorLog::create([
                'message' => $e->getMessage(),
                'level' => $this->getErrorLevel($e),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $dbError) {
            // If database logging fails, just log to file
            Log::error('Failed to log error to database', [
                'original_error' => $e->getMessage(),
                'db_error' => $dbError->getMessage(),
            ]);
        }
    }

    /**
     * Determine error level
     */
    protected function getErrorLevel(Throwable $e): string
    {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return 'info';
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 'warning';
        }

        if ($e instanceof \Illuminate\Database\QueryException) {
            return 'error';
        }

        if ($e instanceof \PDOException || $e instanceof \ErrorException) {
            return 'critical';
        }

        return 'error';
    }

    /**
     * Check if error is critical
     */
    protected function isCritical(Throwable $e): bool
    {
        return in_array($this->getErrorLevel($e), ['critical', 'emergency']);
    }

    /**
     * Notify administrators of critical errors
     */
    protected function notifyCriticalError(Throwable $e): void
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@rezerveet.com');

            Mail::to($adminEmail)->send(new CriticalErrorNotification([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
                'user_id' => auth()->id(),
            ]));
        } catch (\Exception $mailError) {
            Log::error('Failed to send critical error email', [
                'error' => $mailError->getMessage(),
            ]);
        }
    }

    /**
     * Render user-friendly error pages
     */
    public function render($request, Throwable $e)
    {
        // API requests should get JSON responses
        if ($request->expectsJson()) {
            return $this->renderJsonError($request, $e);
        }

        // Web requests get custom error pages
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return redirect()->route('login')
                ->with('error', 'Lütfen giriş yapınız.');
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->view('errors.403', [], 403);
        }

        return parent::render($request, $e);
    }

    /**
     * Render JSON error response
     */
    protected function renderJsonError($request, Throwable $e)
    {
        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        $response = [
            'message' => app()->environment('production')
                ? 'Bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.'
                : $e->getMessage(),
            'status' => $status,
        ];

        if (app()->hasDebugModeEnabled()) {
            $response['exception'] = get_class($e);
            $response['file'] = $e->getFile();
            $response['line'] = $e->getLine();
            $response['trace'] = collect($e->getTrace())->map(function ($trace) {
                return \Illuminate\Support\Arr::except($trace, ['args']);
            })->all();
        }

        return response()->json($response, $status);
    }
}
