<?php

namespace App\Http\Controllers;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $googleAdsClient = null;

    /**
     * @param null $googleAdsClient
     */
    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient =  (new GoogleAdsClientBuilder())
        ->fromFile(config('app.google_ads_php_path'))
        ->withOAuth2Credential((new OAuth2TokenBuilder())
            ->fromFile(config('app.google_ads_php_path'))
            ->build())
        ->build();
    }
}
