<?php


namespace App\Extensions;


use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class ExoUserProvider implements UserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed $identifier
     * @param string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return User::where('RememberToken', $token)->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->RememberToken = $token;
        $user->save();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        $client = new Client();

        try {
            $result = $client->post(env('AUTH_ENDPOINT') . '?user-api&method=login', [
                'form_params' => [
                    'secret' => env('AUTH_SECRET'),
                    'username' => $credentials['username'],
                    'password' => $credentials['password']
                ]
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());
            $user = User::where('ForumID', $response->data->userID)->first();

            if ($user) {
                return $user;
            }
            return null;
        } catch (GuzzleException $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $client = new Client();

        try {
            $result = $client->post(env('AUTH_ENDPOINT') . '?user-api&method=login', [
                'form_params' => [
                    'secret' => env('AUTH_SECRET'),
                    'username' => $credentials['username'],
                    'password' => $credentials['password']
                ]
            ]);

            $response = \GuzzleHttp\json_decode($result->getBody()->getContents());
            $account = User::query()->where('ForumID', $response->data->userID)->first();

            if ($account) {
                if (env('APP_ENV') === 'staging') {
                    if ($account->Rank < 2) {
                        return false;
                    }
                }

                return true;
            }
            return false;
        } catch (GuzzleException $exception) {
            return false;
        }
    }
}
