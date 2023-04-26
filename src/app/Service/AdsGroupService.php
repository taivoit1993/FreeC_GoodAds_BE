<?php

namespace App\Service;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;
use Google\Ads\GoogleAds\V13\Enums\AdGroupTypeEnum\AdGroupType;
use Google\Ads\GoogleAds\V13\Resources\AdGroup;
use Google\Ads\GoogleAds\V13\Services\AdGroupOperation;
use Google\ApiCore\ApiException;

class AdsGroupService
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $campaignId
     * @return mixed
     * @throws \Google\ApiCore\ApiException
     */
    public function createAdsGroup(GoogleAdsClient $googleAdsClient,
                                   int             $customerId,
                                   int             $campaignId)
    {
        try {
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);
            $operations = [];

            // Constructs an ad group and sets an optional CPC value.
            $adGroup = new AdGroup([
                'name' => 'Earth to Mars Cruises #' . time(),
                'campaign' => $campaignResourceName,
                'status' => AdGroupStatus::ENABLED,
                'type' => AdGroupType::SEARCH_STANDARD,
                'cpc_bid_micros' => 10000000
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

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $pageSize
     * @param int|null $campaignId
     * @return array
     * @throws ApiException
     * @throws \Google\ApiCore\ValidationException
     */
    public function listingAdsGroup(GoogleAdsClient $googleAdsClient,
                                    int             $customerId,
                                    ?int            $campaignId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT campaign.id, ad_group.id, ad_group.name,'
                       .' ad_group.cpc_bid_micros, ad_group.status FROM ad_group';
            if ($campaignId !== null) {
                $query .= " WHERE campaign.id = $campaignId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query);
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $data [] = [
                    "id" => $googleAdsRow->getAdGroup()->getId(),
                    "name" => $googleAdsRow->getAdGroup()->getName(),
                    'cpc_bid_micros' => $googleAdsRow->getAdGroup()->getCpcBidMicros(),
                    'status' => AdGroupStatus::name($googleAdsRow->getAdGroup()->getStatus()),
                    "campaign_id" => $googleAdsRow->getCampaign()->getId()
                ];
            }
            return $data;
        } catch (GoogleAdsException $googleAdsException) {
            throw new \Exception($googleAdsException->getMessage());
        }

    }

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $adGroupId
     * @param $bidMicroAmount
     * @return mixed
     * @throws ApiException
     */
    public function updateAdsGroup(GoogleAdsClient $googleAdsClient,
                                   int             $customerId,
                                   int             $adGroupId,
                                                   $bidMicroAmount)
    {
        try {
            $adGroup = new AdGroup([
                'resource_name' => ResourceNames::forAdGroup($customerId, $adGroupId),
                'cpc_bid_micros' => $bidMicroAmount,
                'status' => AdGroupStatus::PAUSED
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
            throw new \Exception($googleAdsException->getMessage());
        }
    }

    public function removeAdGroup(GoogleAdsClient $googleAdsClient,
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
            throw new \Exception($googleAdsException->getMessage());
        }
    }
}
