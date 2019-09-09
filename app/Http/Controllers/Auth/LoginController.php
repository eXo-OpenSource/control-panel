<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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

    protected function sendLoginResponse(Request $request, $user)
    {
        $remember = $request->has('remember');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $client = new Client();

        try {
            $result = $client->post(env('AUTH_ENDPOINT') . '?user-api', [
                'form_params' => [
                    'secret' => env('AUTH_SECRET'),
                    'method' => 'login',
                    'username' => request('username'),
                    'password' => request('password')
                ]
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());
            $account = User::query()->where('ForumID', $response->data->userID)->first();

            if ($account) {
                if (env('APP_ENV') === 'staging') {
                    if ($account->Rank < 2) {
                        return view('auth.login', ['failed' => "Testphase: Login derzeit nicht möglich! Bitte melde dich bei einem Admin."]);
                    }
                }

                return $this->sendLoginResponse($request, $account);
            } else {
                return view('auth.login', ['failed' => "User/Email oder Passwort falsch!"]);
            }

        } catch (GuzzleException $exception) {
            if (isset($exception) && $exception->getCode() == 400) {
                return view('auth.login', ['failed' => "User/Email oder Passwort falsch!"]);
            } else {
                dd($exception);
                return view('auth.login', ['failed' => "Login derzeit nicht möglich! Bitte melde dich bei einem Admin."]);
            };
        }
    }
}
