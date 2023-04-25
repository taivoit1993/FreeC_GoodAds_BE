<?php

namespace App\Service;

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V13\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V13\Services\CampaignBudgetOperation;
use Google\ApiCore\ApiException;

class BudgetService{
    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @return string
     */
    private function addCampaignBudget(GoogleAdsClient $googleAdsClient,
                                       int             $customerId,
                                       int             $amount_micros)
    {
        try {
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
        } catch (GoogleAdsException $googleAdsException) {

        } catch (ApiException $apiException) {
            throw new \Exception($apiException->getMessage());
        }
    }
}
