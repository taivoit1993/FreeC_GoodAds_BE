<?php

namespace App\Containers\Ad\Tasks;

use App\Http\Core\AbstractTasks;
use App\Trait\GoogleAdTrait;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Common\AdTextAsset;
use Google\Ads\GoogleAds\V13\Common\ResponsiveSearchAdInfo;
use Google\Ads\GoogleAds\V13\Enums\ServedAssetFieldTypeEnum\ServedAssetFieldType;
use Google\Ads\GoogleAds\V13\Resources\Ad;
use Google\Ads\GoogleAds\V13\Services\AdOperation;

class UpdateAdTask extends AbstractTasks
{
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adId,
                        string          $headLine1,
                        string          $headLine2,
                        string          $headLine3,
                        string          $description1,
                        string          $description2)
    {
        try {
            // Creates an ad with the specified resource name and other changes.
            $ad = new Ad([
                'resource_name' => ResourceNames::forAd($customerId, $adId),
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    // Update some properties of the responsive search ad.
                    'headlines' => [
                        new AdTextAsset([
                            'text' => $headLine1,
                            'pinned_field' => ServedAssetFieldType::HEADLINE_1
                        ]),
                        new AdTextAsset(['text' => $headLine2]),
                        new AdTextAsset(['text' => $headLine3])
                    ],
                    'descriptions' => [
                        new AdTextAsset(['text' => $description1]),
                        new AdTextAsset([
                            'text' => $description2])
                    ]
                ]),
            ]);

            $adOperation = new AdOperation();
            $adOperation->setUpdate($ad);
            $adOperation->setUpdateMask(FieldMasks::allSetFieldsOf($ad));

            // Issues a mutate request to update the ad.
            $adServiceClient = $googleAdsClient->getAdServiceClient();
            $response = $adServiceClient->mutateAds($customerId, [$adOperation]);

            // Prints the resource name of the updated ad.
            /** @var Ad $updatedAd */
            $updatedAd = $response->getResults()[0];
            return $updatedAd->getResourceName();
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
