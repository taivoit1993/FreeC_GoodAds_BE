<?php

namespace App\Http\Controllers;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
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
        $this->googleAdsClient = $googleAdsClient;
    }
}
