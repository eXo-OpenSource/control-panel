<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function auth()
    {
        $token = request()->get('token');
        $redirect = request()->get('redirect');

        if(!empty($token))
        {
            $parts = explode('.', $token);

            if(count($parts) === 3) {
                $content = $parts[0] . '.' . $parts[1];
                $result = str_replace('=', '', strtr(base64_encode(hash_hmac('sha256', $content, env('JWT_SECRET'), true)), '+/', '-_'));;

                if($result === $parts[2] || hash_hmac('sha256', $content, env('JWT_SECRET')) === $parts[2])
                {
                    $data = json_decode(base64_decode($parts[1]));
                    if(time() < $data->exp)
                    {
                        $user = User::find($data->sub);

                        if($user) {
                            $result = auth()->loginUsingId($user->Id);

                            if(empty($redirect)) {
                                return redirect('/');
                            }
                            return redirect($redirect);
                        }
                    }
                }
            }
        }

        return redirect('/');
    }
}
