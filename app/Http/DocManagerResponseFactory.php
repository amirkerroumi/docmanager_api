<?php
/**
 * Created by PhpStorm.
 * User: a_kerroumi
 * Date: 28/11/2016
 * Time: 11:23
 */

namespace App\Http;

use Laravel\Lumen\Http\ResponseFactory;
use Illuminate\Support\Traits\Macroable;

/*
 * The default response factory provided by Lumen (Laravel\Lumen\HttpResponseFactory)
 * does not allow to use macros,therefore I created this custom class in order to be able to use macros
 * which are defined in App\Providers\DocManagerResponseServiceProvider
 */
class DocManagerResponseFactory extends ResponseFactory
{
    use Macroable;
}