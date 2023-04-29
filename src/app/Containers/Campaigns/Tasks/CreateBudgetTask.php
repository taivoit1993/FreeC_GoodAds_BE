<?php

namespace App\Containers\Campaigns\Tasks;

use App\Http\Core\AbstractTasks;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V13\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V13\Services\CampaignBudgetOperation;

class CreateBudgetTask extends AbstractTasks
{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int $amountMicros
     * @return string|void
     * @throws \Exception
     */
    public function run(GoogleAdsClient $googleAdsClient, int $customerId, int $amountMicros)
    {
        try {
            $budget = new CampaignBudget([
                'name' => 'FreeC Test Campaign Budget #' . time(),
                'delivery_method' => BudgetDeliveryMethod::STANDARD,
                'amount_micros' => $amountMicros
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
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
