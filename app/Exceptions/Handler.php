<?php

namespace App\Exceptions;

use App\Constants\HttpCodeConstant;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        $logMessage = "#00 ".$e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
        logMessage('admin', 'error', $logMessage);

        if ($request->route() && $request->route()->uri() && strpos($request->route()->uri(), 'api') !== false) {
            return response()->json(handleErrorResponse(HttpCodeConstant::UNKNOWN_ERROR, $e->getMessage()), HttpCodeConstant::UNKNOWN_ERROR, [], JSON_UNESCAPED_UNICODE);
        }
        else {
            return parent::render($request, $e);
        }
    }
}
