<?php

namespace App\Containers\Ad\Tasks;

use App\Http\Core\AbstractTasks;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Resources\AdGroupAd;
use Google\Ads\GoogleAds\V13\Services\AdGroupAdOperation;

class UpdateStatusAdGroupAdTask extends AbstractTasks
{
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adGroupId,
                        int             $adId,
                        int             $status)
    {
        try{
            // Creates ad group ad resource name.
            $adGroupAdResourceName = ResourceNames::forAdGroupAd($customerId, $adGroupId, $adId);

            // Creates an ad and sets its status to PAUSED.
            $adGroupAd = new AdGroupAd();
            $adGroupAd->setResourceName($adGroupAdResourceName);
            $adGroupAd->setStatus($status);

            // Constructs an operation that will pause the ad with the specified resource name,
            // using the FieldMasks utility to derive the update mask. This mask tells the Google Ads
            // API which attributes of the ad group you want to change.
            $adGroupAdOperation = new AdGroupAdOperation();
            $adGroupAdOperation->setUpdate($adGroupAd);
            $adGroupAdOperation->setUpdateMask(FieldMasks::allSetFieldsOf($adGroupAd));

            // Issues a mutate request to pause the ad group ad.
            $adGroupAdServiceClient = $googleAdsClient->getAdGroupAdServiceClient();
            $response = $adGroupAdServiceClient->mutateAdGroupAds(
                $customerId,
                [$adGroupAdOperation]
            );

            // Prints the resource name of the paused ad group ad.
            /** @var AdGroupAd $pausedAdGroupAd */
            $pausedAdGroupAd = $response->getResults()[0];
            return $pausedAdGroupAd->getResourceName();
        }catch(GoogleAdsException $googleAdsException){
            $this->responseAdsError($googleAdsException);
        }

    }
}
