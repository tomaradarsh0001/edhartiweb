<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

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
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return redirect()->back()->with('failure', 'You do not have the required permission');
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Throwable $exception)
    {
        // Handle HTTP exceptions with specific status codes
        // if ($exception instanceof HttpExceptionInterface) {
        //     $statusCode = $exception->getStatusCode();

        //     if ($statusCode == 419) {
        //         return response()->view('errors.419', [], 419);
        //     }
        // }

        // Handle throttle requests exception
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many attempts. Please try again later.'
            ], 429);
        }

        // For all other exceptions, fallback to default Laravel rendering
        return parent::render($request, $exception);
    }
}
