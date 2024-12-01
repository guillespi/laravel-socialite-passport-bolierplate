<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Passport\ClientRepository;
use Adaojunior\PassportSocialGrant\SocialGrantUserProvider;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        // Valid providers
        if (!in_array($provider, config('auth.passport.providers'))) {
            return response()->json(['error' => 'Invalid provider'], 400);
        }

        try {

            // Get the user data from the social provider
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Get the user from the database
            $userProvider = app(SocialGrantUserProvider::class);

            // Get the user from the database
            $user = $userProvider->getUserByAccessToken($provider, $socialUser->token, null);

            if (!$user) {
                return response()->json(['error' => 'Authentication failed'], 401);
            }


            // Get the token from the OAuth server
            $http = new \GuzzleHttp\Client();
            $response = $http->post(config('app.url') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'social',
                    'client_id' => config('auth.passport.client_id'),
                    'client_secret' => config('auth.passport.client_secret'),
                    'provider' => $provider,
                    'access_token' => $socialUser->token,
                    'scope' => '*',
                ],
            ]);

            $tokens = json_decode((string)$response->getBody(), true);

            // Envía el token al frontend o devuélvelo como JSON
            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid credentials or provider error: ' . $e->getMessage()], 400);
        }
    }
}
