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

class DocManagerResponseFactory extends ResponseFactory
{
    use Macroable;
}