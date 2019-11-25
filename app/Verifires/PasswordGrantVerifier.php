<?php

namespace App\Verifires;
//use Dingo\Api\Auth\Auth;
use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier{
	public function verify($username, $password)
      {
          $credentials = [
            'email'    => $username,
            'password' => $password,
          ];

          if (Auth::once($credentials)) {
              return Auth::user()->id;
          }

          return false;
      }
}
