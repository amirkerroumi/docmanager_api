<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) { return $app->version(); });

$app->post('v1/oauth/token', 'DocManagerAccessTokenController@issueToken');

$app->post('v1/user', 'UserController@create');

$app->group(
    ['prefix' => 'v1', 'middleware' => 'auth'], function () use ($app)
    {

        $app->get('/helloWorld', function ()
        {
            // Uses Auth Middleware
            return "helloWorld";
        });
    }
);


