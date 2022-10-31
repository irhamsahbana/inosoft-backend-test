<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use App\Libs\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $e);
        }

        $statusCode = 500;
        $msg = 'Internal Server Error';

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            $statusCode = 401;
            $msg = 'Unauthenticated';
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $e->getStatusCode();
            $msg = $e->getMessage();

            if ($statusCode === 404 && $e->getMessage() === '')
                $msg = 'Endpoint Not Found';
        }

        $response = new Response();
        return $response->json(
            null,
            $e->getMessage() ?? $msg,
            $statusCode,
            get_class($e),
            $e->getFile(),
            $e->getLine(),
            $e->getTrace()
        );
    }
}
