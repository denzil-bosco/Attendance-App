<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            if ($request->expectsJson()) {
                $modelClass = $exception->getModel();
                $ids = $exception->getIds();
            $modelName = class_basename($modelClass); // e.g., "User"
            $missingId = $ids ? implode(',', $ids) : 'unknown';

            return response()->json([
                'error' => "{$modelName} with ID(s) {$missingId} not found."
            ], 404);
        }

        // For non-JSON requests fallback to default 404
        abort(404, 'Resource not found');
    }

    return parent::render($request, $exception);
}
}
