<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use Google_Client;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
    * Redirect the user to the Google authentication page
    *
    * @return \Illuminate\Http\Response
    */
    public function redirectToProvider() {
      return Socialite::driver('google')
      ->redirect();
    }

    /**
    * Obtain the user information from Google
    *
    * @return \Illuminate\Http\Response
    */
    public function handleProviderCallback()
    {
      $user = Socialite::driver('google')->user();

      // Set token for the Google API PHP Client
      $google_client_token = [
          'access_token' => $user->token,
          'refresh_token' => $user->refreshToken,
          'expires_in' => $user->expiresIn
      ];

      $client = new Google_Client();
      $client->setApplicationName("OAuth2 Laravel");
      $client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
      $client->setAccessToken(json_encode($google_client_token));

      return view('home', compact('user'));
    }
}
