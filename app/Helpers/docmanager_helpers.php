<?php
/**
 * Created by PhpStorm.
 * User: a_kerroumi
 * Date: 28/11/2016
 * Time: 11:38
 */

if(!function_exists('docmanager_response'))
{
    function docmanager_response()
    {
        return new App\Http\DocManagerResponseFactory();
    }
}