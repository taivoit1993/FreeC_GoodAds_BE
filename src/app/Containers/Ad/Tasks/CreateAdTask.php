<?php

namespace App\Containers\Ad\Tasks;

use App\Trait\GoogleAdTrait;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Common\ResponsiveSearchAdInfo;
use Google\Ads\GoogleAds\V13\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V13\Enums\ServedAssetFieldTypeEnum\ServedAssetFieldType;
use Google\Ads\GoogleAds\V13\Resources\Ad;
use Google\Ads\GoogleAds\V13\Resources\AdGroupAd;
use Google\Ads\GoogleAds\V13\Services\AdGroupAdOperation;

class CreateAdTask
{
    use GoogleAdTrait, ResponseTrait;

    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adGroupId,
                        string          $headLine1,
                        string          $headLine2,
                        string          $headLine3,
                        string          $description1,
                        string          $description2,
                        int             $status)
    {
        try {
            $ad = new Ad([
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    'headlines' => [
                        self::createAdTextAsset(
                            $headLine1,
                            ServedAssetFieldType::HEADLINE_1
                        ),
                        self::createAdTextAsset($headLine2),
                        self::createAdTextAsset($headLine3)
                    ],
                    'descriptions' => [
                        self::createAdTextAsset($description1),
                        self::createAdTextAsset($description2)
                    ],
                    'path1' => 'all-inclusive',
                    'path2' => 'deals'
                ]),
                'final_urls' => ['http://www.example.com'],
            ]);

            // Creates an ad group ad to hold the above ad.
            $adGroupAd = new AdGroupAd([
                'ad_group' => ResourceNames::forAdGroup($customerId, $adGroupId),
                'status' => $status,
                'ad' => $ad
            ]);

            // Creates an ad group ad operation.
            $adGroupAdOperation = new AdGroupAdOperation();
            $adGroupAdOperation->setCreate($adGroupAd);

            // Issues a mutate request to add the ad group ad.
            $adGroupAdServiceClient = $googleAdsClient->getAdGroupAdServiceClient();
            $response = $adGroupAdServiceClient->mutateAdGroupAds($customerId, [$adGroupAdOperation]);

            $createdAdGroupAdResourceName = $response->getResults()[0]->getResourceName();
            return $createdAdGroupAdResourceName;
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
