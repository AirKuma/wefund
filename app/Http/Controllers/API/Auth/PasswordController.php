<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Dingo\Api\Routing\Helpers;

class PasswordController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('auth.password');
    }

    // public function getCsrf_token()
    // {
    //     return csrf_token();
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

    
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->array($response)->setStatusCode(200);
            case Password::INVALID_USER:
                return $this->response->errorBadRequest();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->response->array($response)->setStatusCode(200);
            default:
                return $this->response->errorBadRequest();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
