<?php

namespace App\HTTP\Controllers\Auth;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SignInWithAppleProvider extends AbstractProvider implements ProviderInterface
{

    protected $fields = ['name', 'email'];

    protected function getAuthUrl($state)
    {
        return "";
    }

    protected function getTokenUrl()
    {
        return "https://appleid.apple.com/auth/token";
    }

    protected function generateClientSecret()
    {
        $key = file_get_contents(__DIR__ . '/AuthKey.p8');
        $time = Carbon::now('UTC')->timestamp;
        $endDate = Carbon::now('UTC')->addMinutes(20)->timestamp;

        #keys
        $teamId     =   config('services.apple.team_id');
        $keyId      =   config('services.apple.key_id');
        $clientId   =   config('services.apple.client_id');

        $headers = [
          'kid' => $keyId,
          "alg" => "ES256",
        ];
        $payload = [
          'iss' => $teamId,
          'iat' => $time,
          'exp' => $endDate,
          'aud' => 'https://appleid.apple.com',
          'sub' => $clientId
        ];
          
        $client_secret = JWT::encode($payload, $key, $alg = 'ES256', $keyId, $headers);
        
        return $client_secret;
    }

    public function getUserByToken($token)
    {

        $clientSecret = $this->generateClientSecret();
        $clientId = config('services.apple.client_id');
        
        $payload = [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => $token,
                    'grant_type' => 'authorization_code'
                ],
        ];
        $response = $this->getHttpClient()->post($this->getTokenUrl(), $payload);

        if ($response->getStatusCode() != 200) {
            return $response->getBody();
        }
        $id_token = explode('.', json_decode($response->getBody(), true)['id_token'])[1];
        return json_decode(base64_decode($id_token), true);


    }


    /**
     * Set the user fields to request from Facebook.
     *
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }


    /**
     * Get a Social User instance from a known access token.
     *
     * @param string $token
     * @return \Laravel\Socialite\Two\User
     */
    public function userFromToken($token)
    {
        return $this->mapUserToObject($this->getUserByToken($token));

    }


    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'name' => request()->name ? request()->name : "Apple User",
            'email' => $user['email'] ?? null,
        ]);
    }

}