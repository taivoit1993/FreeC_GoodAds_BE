<?php

namespace App\Containers\GoogleAuthentication\Tasks;

use Laravel\Socialite\Facades\Socialite;

class GenerateGoogleUrlTask
{
    public function run($scopes, $params)
    {
        return Socialite::driver('google')
            ->scopes($scopes)
            ->with($params)
            ->stateless()->redirect()->getTargetUrl();
    }
}
