<?php

namespace App\Http\Controllers;

use App\Exceptions\DocManagerException;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);
        if($validator->fails())
        {
            $errors  = $validator->errors()->toArray();
            throw new DocManagerException(DocManagerException::INVALID_INPUT, 400, null, null, null, true, $errors);
        }
        else
        {
            $user = \App\User::create(['name' => $request->name, 'email' => $request->email, 'password' => password_hash($request->password, PASSWORD_DEFAULT)]);
            return docmanager_response()->success($user);
        }
    }

    public function getUser(Request $request)
    {
        return "OK";
    }
}
