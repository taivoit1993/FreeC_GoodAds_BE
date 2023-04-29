<?php

namespace App\Containers\AdGroup\Tasks;

use App\Http\Core\AbstractTasks;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Resources\AdGroup;
use Google\Ads\GoogleAds\V13\Services\AdGroupOperation;

class UpdateAddGroupTask extends AbstractTasks
{
    use ResponseTrait;
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adGroupId,
                        int             $bidMicroAmount,
                        int             $status,
                        string          $name)
    {
        try {
            $adGroup = new AdGroup([
                'resource_name' => ResourceNames::forAdGroup($customerId, $adGroupId),
                'cpc_bid_micros' => $bidMicroAmount,
                'status' => $status,
                "name" => $name
            ]);

            $adGroupOperation = new AdGroupOperation();
            $adGroupOperation->setUpdate($adGroup);
            $adGroupOperation->setUpdateMask(FieldMasks::allSetFieldsOf($adGroup));

            // Issues a mutate request to update the ad group.
            $adGroupServiceClient = $googleAdsClient->getAdGroupServiceClient();
            $response = $adGroupServiceClient->mutateAdGroups(
                $customerId,
                [$adGroupOperation]
            );
            $updatedAdGroup = $response->getResults()[0];
            return $updatedAdGroup->getResourceName();
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
