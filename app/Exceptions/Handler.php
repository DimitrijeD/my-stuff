<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use \Illuminate\Validation\ValidationException;
use App\Http\Response\ApiResponse;

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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModelDoesntExistException $e, $request) {
            return ApiResponse::error($request, $e);
        });

        $this->renderable(function (UnAuthrorizedException $e, $request) {
            return ApiResponse::error($request, $e);
        });

        $this->renderable(function (InternalServerErrorException $e, $request) {
            return ApiResponse::error($request, $e);
        });

        $this->renderable(function (UnAuthenticatedException $e, $request) {
            return ApiResponse::error($request, $e);
        });

        $this->renderable(function (ModelGoneException $e, $request) {
            return ApiResponse::error($request, $e);
        });
        
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    ApiResponse::MESSAGES => [[ __('auth.mustBeLoggedIn') ]],
                    ApiResponse::TYPE => ApiResponse::ERROR_TYPE
                ], 401);
            }
        });
        
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */ 
    protected function invalidJson($request, ValidationException $exception)
    {
        return ApiResponse::error($request, $exception);
    }
}
