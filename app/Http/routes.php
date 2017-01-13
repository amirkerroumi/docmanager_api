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

$app->post('v1/password/email', 'PasswordController@postEmail');
$app->get('password/reset/{token}', 'PasswordController@showResetForm');
$app->post('password/reset', 'PasswordController@postReset');



/*
 * IMPORTANT NOTE: UNLIKE LARAVEL, IN LUMEN: ROUTE GROUPS DO NOT INHERIT THE PARENT GROUP'S PROPERTIES
 * For instance the namespace 'App\Http\Controllers' is already defined
 * in a group in the config/app.php file. This routes.php file is included inside that group.
 * Any route inside this file will inherit that namespace unless it's a route that is part
 * of a new group defined in this file. Any new group defined in this file will have to specify
 * that namespace again otherwise the Controllers attached to those routes will not be found.
 */
$app->group(
// Uses Auth Middleware
    ['prefix' => 'v1', 'middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () use ($app)
{

    $app->get('/helloWorld', function ()
    {
        return docmanager_response()->success('helloWorld');
    });

    $app->get('/user', 'UserController@getUser');

}
);



