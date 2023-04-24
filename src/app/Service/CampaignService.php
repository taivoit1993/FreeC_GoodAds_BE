<?php

namespace App\Service;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Common\ManualCpc;
use Google\Ads\GoogleAds\V13\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V13\Resources\Campaign;
use Google\Ads\GoogleAds\V13\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V13\Services\CampaignOperation;
use Google\ApiCore\ApiException;

class CampaignService
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $amount_micros
     * @param string $campaign_name
     * @param bool $target_google_search
     * @param bool $target_search_network
     * @param bool $target_content_network
     * @return mixed
     * @throws ApiException
     */
    public function createCampaign(GoogleAdsClient $googleAdsClient,
                                   int             $customerId,
                                   int             $amount_micros,
                                   string          $campaign_name,
                                   bool            $target_google_search,
                                   bool            $target_search_network,
                                   bool            $target_content_network
    )
    {
        try {
            $budgetResourceName = app(BudgetService::class)
                ->addCampaignBudget($googleAdsClient, $customerId, $amount_micros);

            $networkSettings = new NetworkSettings([
                'target_google_search' => $target_google_search,
                'target_search_network' => $target_search_network,
                'target_content_network' => $target_content_network,
                'target_partner_search_network' => false
            ]);

            $campaign = new Campaign([
                'name' => $campaign_name,
                'advertising_channel_type' => AdvertisingChannelType::SEARCH,
                'status' => CampaignStatus::PAUSED,
                'manual_cpc' => new ManualCpc(),
                'campaign_budget' => $budgetResourceName,
                'network_settings' => $networkSettings,
                'start_date' => date('Ymd', strtotime('+1 day')),
                'end_date' => date('Ymd', strtotime('+1 month'))
            ]);

            // Creates a campaign operation.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setCreate($campaign);
            $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns($customerId, [$campaignOperation]);
            $addedCampaign = $response->getResults()[0];
            return $addedCampaign->getResourceName();
        } catch (GoogleAdsException $googleAdsException) {
            throw new \Exception($googleAdsException->getMessage());
        }
    }

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @return array
     * @throws ApiException
     */
    public function listingCampaign(GoogleAdsClient $googleAdsClient,
                                    int             $customerId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            // Creates a query that retrieves all campaigns.
            $query = 'SELECT campaign.id,'
                . ' campaign.name, campaign.status,campaign.start_date, '
                . ' metrics.impressions, metrics.clicks, metrics.ctr, metrics.average_cpc, metrics.cost_micros'
                . ' FROM campaign ORDER BY campaign.id';

            $stream =
                $googleAdsServiceClient->searchStream($customerId, $query);

            $response = [];
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                $response[] = [
                    "id" => $googleAdsRow->getCampaign()->getId(),
                    "name" => $googleAdsRow->getCampaign()->getName(),
                    "status" => CampaignStatus::name($googleAdsRow->getCampaign()->getStatus()),
                    "start_date" => $googleAdsRow->getCampaign()->getStartDate(),
                    "impressions" => $googleAdsRow->getMetrics()->getImpressions(),
                    "clicks" => $googleAdsRow->getMetrics()->getClicks(),
                    "ctr" => $googleAdsRow->getMetrics()->getCtr(),
                    "average_cpc" => $googleAdsRow->getMetrics()->getAverageCPC(),
                    "cost_micros" => $googleAdsRow->getMetrics()->getCostMicros()
                ];
            }
            return $response;
        } catch (GoogleAdsException $googleAdsException) {
            throw new \Exception($googleAdsException->getMessage());
        }
    }

    public function getDetailCampaign(GoogleAdsClient $googleAdsClient,
                                      int             $customerId,
                                      int             $campaignId
    )
    {
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all campaigns.
        $query = 'SELECT campaign.id,'
            . ' campaign.name, campaign.status,campaign.start_date, '
            . ' metrics.impressions, metrics.clicks, metrics.ctr, metrics.average_cpc, metrics.cost_micros'
            . ' FROM campaign Where campaign.id = ' . $campaignId . ' limit 1';
        $googleCampaign =
            $googleAdsServiceClient->search($customerId, $query)
            ->getIterator()
            ->current();


        dd($googleCampaign->getCampaign()->getId());
        return $googleCampaign->getCampaign()->getId();
    }

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $campaignId
     * @return string
     * @throws ApiException
     */
    public function removeCampaign(GoogleAdsClient $googleAdsClient,
                                   int             $customerId,
                                   int             $campaignId)
    {
        try {
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);

            // Creates a campaign operation.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setRemove($campaignResourceName);

            // Issues a mutate request to remove the campaign.
            $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns($customerId, [$campaignOperation]);

            /** @var Campaign $removedCampaign */
            $removedCampaign = $response->getResults()[0];
            return $removedCampaign->getResourceName();
        } catch (GoogleAdsException $googleAdsException) {
            throw new \Exception($googleAdsException->getMessage());
        }
    }

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $campaignId
     * @return string
     * @throws ApiException
     */
    public function updateCampaign(GoogleAdsClient $googleAdsClient,
                                   int             $customerId,
                                   int             $campaignId)
    {
        // Creates a campaign object with the specified resource name and other changes.
        $campaign = new Campaign([
            'resource_name' => ResourceNames::forCampaign($customerId, $campaignId),
            'status' => CampaignStatus::PAUSED,
            'name' => "Campaing Change" . time()
        ]);

        $campaignOperation = new CampaignOperation();
        $campaignOperation->setUpdate($campaign);
        $campaignOperation->setUpdateMask(FieldMasks::allSetFieldsOf($campaign));

        // Issues a mutate request to update the campaign.
        $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();
        $response = $campaignServiceClient->mutateCampaigns(
            $customerId,
            [$campaignOperation]
        );

        // Prints the resource name of the updated campaign.
        /** @var Campaign $updatedCampaign */
        $updatedCampaign = $response->getResults()[0];
        return $updatedCampaign->getResourceName();
    }
}
