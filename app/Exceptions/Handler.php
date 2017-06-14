<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
    \Illuminate\Auth\AuthenticationException::class,
    \Illuminate\Auth\Access\AuthorizationException::class,
    \Symfony\Component\HttpKernel\Exception\HttpException::class,
    \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    \Illuminate\Session\TokenMismatchException::class,
    \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        // If the request wants JSON (AJAX doesn't always want JSON)
        if ($request->wantsJson() || $request->isJson() || true) {
            // Define the response
            $response = [
                'status' => "error",
                'status_code' => 400,
                'message' => 'Sorry, something went wrong.'
            ];

            // If the app is in debug mode
            // if (config('app.debug'))
            // {
                // Add the exception class name, message and stack trace to response
            $response['message'] = $exception->getMessage();
            if (config('app.debug')) {
                $response['exception'] = get_class($exception); // Reflection might be better here
                $response['trace'] = $exception->getTrace();
            }
            // }

            // If this exception is an instance of HttpException
            if ($exception instanceof HttpException || get_class( $exception) == "Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException")
            {
                // Grab the HTTP status code from the Exception
                return response()->json(['status' => 'error', 'status_code' => 404, 'message' => 'URL Not found'], $exception->getStatusCode());
                //$status = $exception->getStatusCode();
            }


            // Return a JSON response with the response array and status code
            return response()->json($response, $response['status_code']);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

}
