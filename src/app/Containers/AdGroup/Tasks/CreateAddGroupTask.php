<?php
namespace App\Containers\AdGroup\Tasks;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;
use Google\Ads\GoogleAds\V13\Enums\AdGroupTypeEnum\AdGroupType;
use Google\Ads\GoogleAds\V13\Resources\AdGroup;
use Google\Ads\GoogleAds\V13\Services\AdGroupOperation;
use Google\ApiCore\ApiException;

class CreateAddGroupTask
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $campaignId
     * @param string $name
     * @param int $cpcBidMicros
     * @return mixed
     * @throws \Exception
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $campaignId,
                        string          $name,
                        int             $cpcBidMicros)
    {
        try {
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);
            $operations = [];

            // Constructs an ad group and sets an optional CPC value.
            $adGroup = new AdGroup([
                'name' => $name,
                'campaign' => $campaignResourceName,
                'status' => AdGroupStatus::ENABLED,
                'type' => AdGroupType::SEARCH_STANDARD,
                'cpc_bid_micros' => $cpcBidMicros
            ]);

            $adGroupOperation = new AdGroupOperation();
            $adGroupOperation->setCreate($adGroup);
            $operations[] = $adGroupOperation;

            // Issues a mutate request to add the ad groups.
            $adGroupServiceClient = $googleAdsClient->getAdGroupServiceClient();
            $response = $adGroupServiceClient->mutateAdGroups(
                $customerId,
                $operations
            );

            return $response->getResults()[0]->getResourceName();
        } catch (ApiException $apiException) {
            throw new \Exception($apiException->getMessage());
        }
    }
}
