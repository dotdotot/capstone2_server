<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        ExpiredException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        DB::rollback();
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') && !app()->environment('local') && !app()->environment('testing')) {
                $request = app('request');

                \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($request, $e): void {
                    if ($request->get('company') !== null) {
                        $scope->setContext('company', $request->get('company')->toArray());
                    }
                    if ($request->get('user') !== null) {
                        $scope->setContext('user', $request->get('user')->toArray());
                    }
                    if ($request->get('decoded') !== null) {
                        $scope->setContext('decoded', (array)$request->get('decoded'));
                    }

                    $trace = [];
                    if (!empty($e->getTrace())) {
                        $trace = [
                            'class' => isset($e->getTrace()[0]['class']) ? $e->getTrace()[0]['class'] : 'Unknown class',
                            'function' => isset($e->getTrace()[0]['function']) ? $e->getTrace()[0]['function'] : 'Unknown function',
                        ];
                    }

                    $scope->setContext('trace', $trace);
                });

                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        DB::rollback();
        if ($e instanceof NotFoundHttpException) {
            $message = empty($e->getMessage()) ? 'page not found' : $e->getMessage();
            return new JsonResponse(['message' => $message], 404);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return new JsonResponse([
                'message' => 'Method not allowed'
            ], 405);
        } elseif ($e instanceof ValidationException) {
            $message = 'Valiation error';
            $keys = array_keys($e->errors());
            if (count($keys) > 0 && count($e->errors()[$keys[0]]) > 0) {
                $message = $e->errors()[$keys[0]][0];
            }

            $data = [
                'message' => $message,
                'errors' => $e->errors()
            ];
            if (in_array(env('APP_ENV'), ['local', 'testing', 'development'])) {
                $data['file'] = $e->getFile() . '::' . $e->getLine();
            }
            return new JsonResponse($data, 422);
        } elseif ($e instanceof ExpiredException) {
            return new JsonResponse([
                'message' => __('aborts.logout_or_long_term_inactivity')
            ], 419);
        } elseif ($e instanceof SignatureInvalidException) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 403);
        } elseif ($e instanceof ModelNotFoundException) {
            return new JsonResponse([
                'message' => 'Model not found'
            ], 404);
        } elseif ($e instanceof QueryException) {
            $response = [
                'message' => 'Database query error'
            ];
            if (app()->environment('local') || app()->environment('testing') || app()->environment('development')) {
                $response['errors'] = $e->getMessage();
            }

            return new JsonResponse($response, 500);
        } elseif ($e instanceof HttpException) {
            $response = [
                'message' => $e->getMessage()
            ];
            // 410 응답 시
            if ($e->getStatusCode() === 410 && is_array($e->getHeaders()) && count($e->getHeaders()) > 0) {
                $response['type'] = $e->getHeaders()[0];
            }
            // 426 응답 시
            if ($e->getStatusCode() === 426) {
                $response['type'] = $e->getHeaders()[0];
            }
            if (in_array(env('APP_ENV'), ['local', 'testing', 'development'])) {
                $response['trace'] = array_slice($e->getTrace(), 0, 2);
            }

            return new JsonResponse($response, $e->getStatusCode());
        } else {
            $parentRender = parent::render($request, $e);
        }

        if ($parentRender instanceof JsonResponse) {
            return $parentRender;
        } else {
            $response = [
                'message' => $e->getMessage()
            ];

            // $e->getHeaders() 을 통해서 abort 추가 속성 조회 가능

            if (in_array(env('APP_ENV'), ['local', 'testing', 'development']) && $parentRender->status() >= 500) {
                $response['file'] = $e->getFile() . '::' . $e->getLine();
            }

            return new JsonResponse($response, $parentRender->status());
        }
    }
}
