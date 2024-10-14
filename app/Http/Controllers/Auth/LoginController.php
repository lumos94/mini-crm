<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Handle the user authentication and generate API token.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Generate a new API token (plain-text)
        $plainTextToken = Str::random(60);

        // Hash the token and store it in the database
        $user->forceFill([
            'api_token' => hash('sha256', $plainTextToken),
        ])->save();

        // Return the plain-text token to the frontend
        return response()->json(['token' => $plainTextToken]);
    }
}
