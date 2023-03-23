<?php

declare(strict_types=1);

namespace App\Exceptions;

use Arr;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        $response = parent::render($request, $e);

        if ($request->is('api/*')) {
            return $this->apiResponse($e, $response);
        }

        return $response;
    }

    private function apiResponse(Throwable $e, Response $response): Response
    {
        $showError = $e instanceof NotFoundHttpException
            || $e instanceof ModelNotFoundException
            || $e instanceof ValidationException
            || $e instanceof AuthenticationException
            || $e instanceof JWTException;

        if ($showError) {
            /** @var \Illuminate\Http\Response $response */
            $data = $response->getOriginalContent();
            $data = is_array($data) ? $data : [];
            $data = [
                'message' => $e->getMessage(),
                'errors' => Arr::get($data, 'errors'),
            ];

            return response()->json($data, $response->getStatusCode());
        }

        $data = ['message' => 'Internal server error'];

        return response()->json($data, HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR);
    }
}
