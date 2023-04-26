<?php

namespace App\Containers\Campaigns\Tasks;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use Google\ApiCore\ApiException;

class ListingCampaignTask
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @return array
     * @throws ApiException
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            // Creates a query that retrieves all campaigns.
            $query = 'SELECT campaign.id,'
                . ' campaign.name, campaign.status,campaign.start_date, campaign.end_date, '
                . ' metrics.impressions, metrics.clicks, metrics.ctr, metrics.average_cpc, metrics.cost_micros, '
                . ' campaign_budget.amount_micros'
                . ' FROM campaign ORDER BY campaign.id desc';

            $stream = $googleAdsServiceClient
                ->search($customerId, $query)->getPage();
            $ads = [];
            foreach ($stream->getIterator() as $googleAdsRow) {
                $ads[] = [
                    "id" => $googleAdsRow->getCampaign()->getId(),
                    "name" => $googleAdsRow->getCampaign()->getName(),
                    "status" => CampaignStatus::name($googleAdsRow->getCampaign()->getStatus()),
                    "start_date" => $googleAdsRow->getCampaign()->getStartDate(),
                    "end_date" => $googleAdsRow->getCampaign()->getEndDate(),
                    "impressions" => $googleAdsRow->getMetrics()->getImpressions(),
                    "clicks" => $googleAdsRow->getMetrics()->getClicks(),
                    "ctr" => $googleAdsRow->getMetrics()->getCtr(),
                    "average_cpc" => $googleAdsRow->getMetrics()->getAverageCPC(),
                    "cost_micros" => $googleAdsRow->getMetrics()->getCostMicros(),
                    "amount_micros" => $googleAdsRow->getCampaignBudget()->getAmountMicros()
                ];
            }
            return $ads;
        } catch (GoogleAdsException $googleAdsException) {
            throw new \Exception($googleAdsException->getMessage());
        }
    }
}
