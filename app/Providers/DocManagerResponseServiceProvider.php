<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;

use Illuminate\Support\Facades\Response;
//use Illuminate\Contracts\Routing\ResponseFactory;
use Laravel\Lumen\Http\ResponseFactory;

use App\Http\DocManagerResponseFactory;

class DocManagerResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @param ResponseFactory $response
     * @return void
     */
    public function boot(DocManagerResponseFactory $response)
    {
        $response->macro(
            'success', function($data, $metadata = [], $status = 200) use($response){

            return $response->json([
                'timestamp' => microtime(true),
                'success' => true,
                'status' => $status,
                'metadata' => $metadata,
                'data' => $data

            ]);
        });

        $response->macro(
            'error', function($error) use($response){

            return $response->json([
                'timestamp' => microtime(true),
                'success' => false,
                'status' => $error->getCode(),
                'code' => $error->getCustomCode(),
                'message' => $error->getMessage(),
                'hint' => $error->getCustomHint(),
                'error_type' => $error->getErrorType(),
                'user_messages' => $error->getUserMessages()
            ]);
        });

        $response->macro(
            'php_error', function($error) use($response){
            $statusCode = ($error->getCode() > 0) ? $error->getCode() : 500;
            return $response->json([
                'timestamp' => microtime(true),
                'success' => false,
                'status' => $statusCode,
                'message' => $error->getMessage(),
                'error_type' => 'php_error',
                'error_file' => $error->getFile(),
                'error_line' => $error->getLine(),
                'error_trace_string' => $error->getTraceAsString()
            ]);
        });
    }
}
