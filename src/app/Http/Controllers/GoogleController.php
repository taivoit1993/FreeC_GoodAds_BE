<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private function getClient(): \Google_Client
    {
        // load our config.json that contains our credentials for accessing google's api as a json string
        $configJson = base_path().'/config.json';

        // define an application name
        $applicationName = 'freeC App';

        // create the client
        $client = new \Google_Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline'); // necessary for getting the refresh token
        $client->setApprovalPrompt ('force'); // necessary for getting the refresh token
        // scopes determine what google endpoints we can access. keep it simple for now.
        $client->setScopes(
            [
                "https://www.googleapis.com/auth/adwords"
            ]
        );
        $client->setIncludeGrantedScopes(true);
        return $client;
    }
    //
    public function loginurl(){
        return response()->json([
            'url' => Socialite::driver('google')
                ->scopes(["https://www.googleapis.com/auth/adwords"])
                ->with(["include_granted_scopes" => "true",'access_type' => 'offline', "prompt" => "consent select_account"])
                ->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function loginCallback(){
        $googleUser = Socialite::driver('google')->stateless()->user();
        dd($googleUser);
        $user = null;
    }
}
