<?php

namespace App\Containers\GoogleAuthentication\Tasks;

use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginCallbackTask
{
    public function run()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        return SocialAccount::updateOrCreate(
            ['social_id' => $googleUser->getId()],
            [
                'social_name' => $googleUser->name,
                'social_email' => $googleUser->email,
                'social_avatar' => $googleUser->avatar,
                'token' => $googleUser->token,
                'refreshToken' => $googleUser->refreshToken
            ]
        );
    }
}
