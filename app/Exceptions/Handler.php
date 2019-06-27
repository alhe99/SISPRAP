<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException as AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
 
        return redirect()->guest(route('showLogin'));
    }

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
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
    public function render($request, Exception $exception)
    {
        //Retornando las vistas personalizadas para errores 500 y 404
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                return response()->view('errors.' . '404', [], 404);
            }
         
            if ($exception->getStatusCode() == 500) {
                return response()->view('errors.' . '500', [], 500);
            }
        }

        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
            
        } else {
            // Custom error 500 view on production
            if (app()->environment() == 'production') {
                return response()->view('auth.login', [], 200);
            }
            return parent::render($request, $exception);
        }

    }
}
