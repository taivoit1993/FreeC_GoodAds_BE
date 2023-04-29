<?php
namespace App\Containers\Ad\Tasks;

use App\Http\Core\AbstractTasks;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\AdGroupAdOperation;

class DeleteAddTask extends AbstractTasks
{
    public function run( GoogleAdsClient $googleAdsClient,
                         int $customerId,
                         int $adGroupId,
                         int $adId){
        try{
            // Creates ad group ad resource name.
            $adGroupAdResourceName = ResourceNames::forAdGroupAd($customerId, $adGroupId, $adId);

            // Constructs an operation that will remove the ad with the specified resource name.
            $adGroupAdOperation = new AdGroupAdOperation();
            $adGroupAdOperation->setRemove($adGroupAdResourceName);

            // Issues a mutate request to remove the ad group ad.
            $adGroupAdServiceClient = $googleAdsClient->getAdGroupAdServiceClient();
            $response = $adGroupAdServiceClient->mutateAdGroupAds(
                $customerId,
                [$adGroupAdOperation]
            );

            $removedAdGroupAd = $response->getResults()[0];
            return  $removedAdGroupAd->getResourceName();
        }catch(GoogleAdsException $googleAdsException){
            $this->responseAdsError($googleAdsException);
        }

    }
}
