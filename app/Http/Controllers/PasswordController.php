<?php
/**
 * Created by PhpStorm.
 * User: a_kerroumi
 * Date: 12/01/2017
 * Time: 15:19
 */

namespace App\Http\Controllers;


use App\Http\Controllers\ResetsPasswords;

class PasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->broker = 'users';
    }
}