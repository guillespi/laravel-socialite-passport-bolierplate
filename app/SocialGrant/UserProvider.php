<?php

namespace App\SocialGrant;

use Adaojunior\PassportSocialGrant\SocialGrantUserProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class UserProvider implements SocialGrantUserProvider
{
    /**
     * Retrieve a user by provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return Authenticatable|null
     */
    public function getUserByAccessToken(string $provider, string $accessToken, ?ClientEntityInterface $client):? Authenticatable
    {
        try {
            // Use Socialite to get the user data from the provider
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($accessToken);
        } catch (\Exception $e) {
            return null;
        }

        // Look for an existing user by provider_id and provider
        $socialAccount = SocialAccount::where('social_name', $provider)
            ->where('social_id', $socialUser->getId())
            ->first();

        // If the social account already exists, update the user data and return the user
        if ($socialAccount) {
            $socialAccount->user->update([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
            ]);
            return $socialAccount->user;
        }

        // If no social account is found, search by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            SocialAccount::create([
                'social_name' => $provider,
                'social_id' => $socialUser->getId(),
                'user_id' => $user->id,
            ]);
        } else {
            // If no user is found, create a new user and social account
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                // Set a safe random password for users that register with social providers
                'password' => bcrypt(Str::random(16)),
            ]);
            $user->socialAccounts()->create([
                'social_name' => $provider,
                'social_id' => $socialUser->getId(),
            ]);
        }

        return $user;

    }
}
