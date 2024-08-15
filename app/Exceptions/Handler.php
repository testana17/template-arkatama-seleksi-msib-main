<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ResponseFormatter;
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
            if (! $request->ajax()) {
                return redirect()->back();
            } else {
                return ResponseFormatter::error('Data tidak ditemukan', code: 404);
            }
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException && $request->wantsJson()) {
            return ResponseFormatter::error('Unauthorized', code: 401);
        } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException || $exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException && $request->ajax()) {
            return ResponseFormatter::error('Anda tidak memiliki akses untuk melakukan ini', code: 403);
        }

        return parent::render($request, $exception);
    }
}
