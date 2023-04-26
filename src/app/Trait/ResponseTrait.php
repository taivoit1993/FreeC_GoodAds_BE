<?php
namespace App\Trait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Common\AdTextAsset;

trait ResponseTrait{
    public function responseErrorGoogleAds(GoogleAdsException $googleAdsException){
        $data = [];
        foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
            $data[] = [
                "errorCode" => $error->getErrorCode()->getErrorCode(),
                "errorMessage" =>  $error->getMessage()
            ];
        }
        return response()->json($data,500);
    }
}
