<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GoogleAdsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $token = $request->header('google-token');
            if ($token) {
                $googleAdsClient = (new GoogleAdsClientBuilder())
                    ->withDeveloperToken(env('DEVELOP_TOKEN',""))
                    ->withLoginCustomerId(env('LOGIN_CUSTOMER_ID',""))
                    ->withOAuth2Credential((new OAuth2TokenBuilder())
                        ->withClientId(env('GOOGLE_CLIENT_ID', ""))
                        ->withClientSecret(env('GOOGLE_CLIENT_SECRET', ""))
                        ->withRefreshToken($token)
                        ->build())
                    ->build();
                $request->route()->setParameter('googleAdsClient', $googleAdsClient);
                return $next($request);
            }
            return response()->json( [ 'error' => 'Unauthorized' ], 401 );
        }catch(Exception $e){
            return response()->json( [ 'error' => 'Unauthorized' ], 401 );
        }
    }
}
