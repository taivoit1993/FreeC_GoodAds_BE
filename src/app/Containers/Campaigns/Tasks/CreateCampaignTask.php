<?php

namespace App\Containers\Campaigns\Tasks;

use App\Service\BudgetService;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Common\ManualCpc;
use Google\Ads\GoogleAds\V13\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V13\Resources\Campaign;
use Google\Ads\GoogleAds\V13\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V13\Services\CampaignOperation;

class CreateCampaignTask
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $amountMicros
     * @param string $campaignName
     * @param bool $targetGoogleSearch
     * @param bool $targetSearchNetwork
     * @param bool $targetContentNetwork
     * @param bool $targetPartnerSearchNetwork
     * @return mixed
     * @throws \Google\ApiCore\ApiException
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $amountMicros,
                        string          $campaignName,
                        string          $status,
                        string          $startDate,
                        string          $endDate,
    )
    {
        try {
            $budgetResourceName = app(CreateBudgetTask::class)
                ->run($googleAdsClient, $customerId, $amountMicros);

            $networkSettings = new NetworkSettings([
                'target_google_search' => true,
                'target_search_network' => true,
                'target_content_network' => true,
                'target_partner_search_network' => false
            ]);

            $campaign = new Campaign([
                'name' => $campaignName,
                'advertising_channel_type' => AdvertisingChannelType::SEARCH,
                'status' => $status,
                'manual_cpc' => new ManualCpc(),
                'campaign_budget' => $budgetResourceName,
                'network_settings' => $networkSettings,
                'start_date' => $startDate,
                'end_date' => $endDate
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
}
