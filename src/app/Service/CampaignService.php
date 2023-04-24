<?php
namespace App\Service;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Common\ManualCpc;
use Google\Ads\GoogleAds\V13\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V13\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V13\Resources\Campaign;
use Google\Ads\GoogleAds\V13\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V13\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V13\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V13\Services\CampaignOperation;
use Google\ApiCore\ApiException;


class CampaignService{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @return string
     */
    private function addCampaignBudget(GoogleAdsClient $googleAdsClient,
                                       int $customerId, int $amount_micros){
        try{
            $budget = new CampaignBudget([
                'name' => 'FreeC Test Campaign Budget #' . time(),
                'delivery_method' => BudgetDeliveryMethod::STANDARD,
                'amount_micros' => $amount_micros
            ]);

            // Creates a campaign budget operation.
            $campaignBudgetOperation = new CampaignBudgetOperation();
            $campaignBudgetOperation->setCreate($budget);
            // Issues a mutate request.
            $campaignBudgetServiceClient = $googleAdsClient->getCampaignBudgetServiceClient();
            $response = $campaignBudgetServiceClient->mutateCampaignBudgets(
                $customerId,
                [$campaignBudgetOperation]
            );

            /** @var CampaignBudget $addedBudget */
            $addedBudget = $response->getResults()[0];

            return $addedBudget->getResourceName();
        }catch(GoogleAdsException $googleAdsException){

        } catch (ApiException $apiException) {
            throw new \Exception($apiException->getMessage());
        }

    }

    public function createCampaign(GoogleAdsClient $googleAdsClient,
                                   int $customerId,
                                   int $amount_micros,
                                    string $campaign_name,
                                    bool $target_google_search,
                                    bool $target_search_network,
                                    bool $target_content_network,
                                bool $target_partner_search_network
    ){
        try{
            $budgetResourceName = $this->addCampaignBudget($googleAdsClient, $customerId, $amount_micros);

            $networkSettings = new NetworkSettings([
                'target_google_search' => $target_google_search,
                'target_search_network' => $target_search_network,
                // Enables Display Expansion on Search campaigns. See
                // https://support.google.com/google-ads/answer/7193800 to learn more.
                'target_content_network' => $target_content_network,
                'target_partner_search_network' => $target_partner_search_network
            ]);

            $campaign = new Campaign([
                'name' => $campaign_name,
                'advertising_channel_type' => AdvertisingChannelType::SEARCH,
                // Recommendation: Set the campaign to PAUSED when creating it to prevent
                // the ads from immediately serving. Set to ENABLED once you've added
                // targeting and the ads are ready to serve.
                'status' => CampaignStatus::PAUSED,
                // Sets the bidding strategy and budget.
                'manual_cpc' => new ManualCpc(),
                'campaign_budget' => $budgetResourceName,
                // Adds the network settings configured above.
                'network_settings' => $networkSettings,
                // Optional: Sets the start and end dates.
                'start_date' => date('Ymd', strtotime('+1 day')),
                'end_date' => date('Ymd', strtotime('+1 month'))
            ]);

            // Creates a campaign operation.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setCreate($campaign);
            $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns($customerId, [$campaignOperation]);
            return $response;
        }catch(GoogleAdsException $googleAdsException){
            throw new \Exception($googleAdsException->getMessage());
        }
    }

    public function listingCapaign(GoogleAdsClient $googleAdsClient,
                                   int $customerId){
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all campaigns.
        $query = 'SELECT campaign.id,'
                .' campaign.name, campaign.status,campaign.start_date, '
                .' metrics.impressions, metrics.clicks, metrics.ctr, metrics.average_cpc, metrics.cost_micros'
                .' FROM campaign ORDER BY campaign.id';

        $stream =
            $googleAdsServiceClient->searchStream($customerId, $query);

        $response = [];
        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            $response[] = [
                "id" => $googleAdsRow->getCampaign()->getId(),
                "name" => $googleAdsRow->getCampaign()->getName(),
                "status" => CampaignStatus::name($googleAdsRow->getCampaign()->getStatus()),
                "start_date" => $googleAdsRow->getCampaign()->getStartDate(),
                "impressions" =>  $googleAdsRow->getMetrics()->getImpressions(),
                "clicks" => $googleAdsRow->getMetrics()->getClicks(),
                "ctr" => $googleAdsRow->getMetrics()->getCtr(),
                "average_cpc" => $googleAdsRow->getMetrics()->getAverageCPC(),
                "cost_micros" => $googleAdsRow->getMetrics()->getCostMicros()
            ];
        }
        return $response;
    }
}
