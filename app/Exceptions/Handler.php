<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    public function handleException($request, Throwable $exception)
    {

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->response(null, 'The specified method for the request is invalid', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof NotFoundHttpException) {
            $errMsg = $exception->getMessage() ?: '404 Not Found';
            return $this->response(null, $errMsg, Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->response(null, "404 not found", Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof BadRequestException) {
            $errMsg = $exception->getMessage() ?: "Bad request";
            return $this->response(null, $errMsg, Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof UnauthorizedHttpException) {
            $errMsg = $exception->getMessage() ? $exception->getMessage() : 'Not authorized';
            return $this->response(null, $errMsg, Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof HttpException) {
            return $this->response(null, $exception->getMessage(), $exception->getStatusCode());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errResponse($request, "Unexpected Exception. Try later");
    }

}
