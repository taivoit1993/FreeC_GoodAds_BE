<?php

namespace App\Containers\GoogleAuthentication;

use App\Containers\GoogleAuthentication\Actions\GenerateGoogleUrlAction;
use App\Containers\GoogleAuthentication\Actions\GoogleLoginCallbackAction;
use Illuminate\Routing\Controller as BaseController;

class  Controller extends BaseController
{
    //
    public function loginurl(){
        $scopes = ["https://www.googleapis.com/auth/adwords"];
        $params = ["include_granted_scopes" => "true",'access_type' => 'offline', "prompt" => "consent select_account"];
        $url = app(GenerateGoogleUrlAction::class)->run($scopes,$params);
        return response()->json([
            'url' => $url
        ]);
    }

    public function loginCallback(){
        $socialAccount = app(GoogleLoginCallbackAction::class)->run();
        return response()->json(["data" => $socialAccount]);
    }
}
