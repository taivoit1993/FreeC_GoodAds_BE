<?php

namespace App\Containers\AdGroup\Tasks;

use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\AdGroupOperation;
use Google\Exception;

/**
 *
 */
class DeleteAddGroupTask
{
    use ResponseTrait;
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $adGroupId
     * @return mixed
     * @throws \Google\ApiCore\ApiException
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adGroupId)
    {

        try {
            // Creates ad group resource name.
            $adGroupResourceName = ResourceNames::forAdGroup($customerId, $adGroupId);

            $adGroupOperation = new AdGroupOperation();
            $adGroupOperation->setRemove($adGroupResourceName);

            // Issues a mutate request to remove the ad group.
            $adGroupServiceClient = $googleAdsClient->getAdGroupServiceClient();
            $response = $adGroupServiceClient->mutateAdGroups(
                $customerId,
                [$adGroupOperation]
            );
            $removedAdGroup = $response->getResults()[0];
            return $removedAdGroup->getResourceName();
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
