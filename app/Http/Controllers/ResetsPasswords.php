<?php
/**
 * Created by PhpStorm.
 * User: a_kerroumi
 * Date: 12/01/2017
 * Time: 15:16
 */


namespace App\Http\Controllers;

use App\Exceptions\DocManagerException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

trait ResetsPasswords
{
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if($validator->fails())
        {
            $errors = $validator->errors()->toArray();
            throw new DocManagerException(DocManagerException::INVALID_INPUT, 400, null, null, null, true, $errors);
        }

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return docmanager_response()::success();
            //TODO
//                return view('auth.passwords.reset', ['password_reset' => true]);
            case Password::INVALID_USER:
                throw new DocManagerException(DocManagerException::INVALID_INPUT, 400, null, null, null, true, ['email' => 'No account is registered for this email']);
            default:
                throw new DocManagerException(DocManagerException::FAILED_PASSWORD_RESET_REQUEST, 500);
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        return response()->json(['success' => true]);
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailFailureResponse($response)
    {
        return response()->json(['success' => false]);
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getResetValidationRules());
        if($validator->fails())
        {
            $errors  = $validator->errors()->toArray();
            return view('auth.passwords.reset', ['errors' => $errors, 'token' => $request->input('token'), 'email' => $request->input('email')]);
        }
//        $this->validate($request, $this->getResetValidationRules());

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                //return $this->getResetSuccessResponse($response);
                return view('auth.passwords.reset', ['password_reset' => true]);
            case Password::INVALID_TOKEN:
                $validator->errors()->add('token','Expired Token');
//                return redirect('/password/reset')->withInput()->withErrors($validator);
                return view('auth.passwords.reset', ['errors' => $validator->errors()->toArray(), 'token' => $request->input('token'), 'email' => $request->input('email')]);
            case Password::INVALID_USER:
                //User does not exist
                $validator->errors()->add('email','User does not exist');
                return view('auth.passwords.reset', ['errors' => $validator->errors()->toArray(), 'token' => $request->input('token'), 'email' => $request->input('email')]);
            default:
                return view('errors.404', ['errorMsg' => "It seems like something went wrong... Please try again."]);
//                return $this->getResetFailureResponse($request, $response);
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);;

        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        return response()->json(['success' => true]);
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
        return response()->json(['success' => false]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token]
        );
    }
}