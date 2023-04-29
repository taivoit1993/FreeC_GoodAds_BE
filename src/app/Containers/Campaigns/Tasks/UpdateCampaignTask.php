<?php

namespace App\Containers\Campaigns\Tasks;

use App\Http\Core\AbstractTasks;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Resources\Campaign;
use Google\Ads\GoogleAds\V13\Services\CampaignOperation;

class UpdateCampaignTask extends AbstractTasks
{
    use ResponseTrait;
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $campaignId,
                        string          $status,
                        string          $name)
    {
        try{
            // Creates a campaign object with the specified resource name and other changes.
            $campaign = new Campaign([
                'resource_name' => ResourceNames::forCampaign($customerId, $campaignId),
                'status' => $status,
                'name' => "update name campaign 199827463"
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
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
