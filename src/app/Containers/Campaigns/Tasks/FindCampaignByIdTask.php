<?php

namespace App\Containers\Campaigns\Tasks;

use App\Http\Core\AbstractTasks;
use App\Trait\ResponseTrait;
use Carbon\Carbon;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;

class FindCampaignByIdTask extends AbstractTasks
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int|null $campaignId
     * @return array
     * @throws \Google\ApiCore\ApiException
     * @throws \Google\ApiCore\ValidationException
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        ?int            $campaignId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            // Creates a query that retrieves all campaigns.
            $query = 'SELECT campaign.id,'
                . ' campaign.name, campaign.status,campaign.start_date, campaign.end_date, '
                . ' metrics.impressions, metrics.clicks, metrics.ctr, metrics.average_cpc, metrics.cost_micros, campaign_budget.amount_micros '
                . ' FROM campaign Where campaign.id = ' . $campaignId . ' limit 1';
            $googleCampaign =
                $googleAdsServiceClient->search($customerId, $query)
                    ->getIterator()
                    ->current();

            return [
                "id" => $googleCampaign->getCampaign()->getId(),
                "name" => $googleCampaign->getCampaign()->getName(),
                "status" => $googleCampaign->getCampaign()->getStatus(),
                "start_date" => $googleCampaign->getCampaign()->getStartDate(),
                "end_date" => $googleCampaign->getCampaign()->getEndDate(),
                "impressions" => $googleCampaign->getMetrics()->getImpressions(),
                "clicks" => $googleCampaign->getMetrics()->getClicks(),
                "ctr" => $googleCampaign->getMetrics()->getCtr(),
                "average_cpc" => $googleCampaign->getMetrics()->getAverageCPC(),
                "cost_micros" => $googleCampaign->getMetrics()->getCostMicros(),
                "amount_micros" => $googleCampaign->getCampaignBudget()->getAmountMicros()
            ];
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }

    }
}
