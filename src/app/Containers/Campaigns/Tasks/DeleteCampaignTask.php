<?php
namespace App\Containers\Campaigns\Tasks;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Resources\Campaign;
use Google\Ads\GoogleAds\V13\Services\CampaignOperation;


class DeleteCampaignTask{
    use ResponseTrait;

    /**
     * @param GoogleAdsClient $googleAdsClient
     * @param int $customerId
     * @param int|null $campaignId
     * @return string|void
     * @throws \Google\ApiCore\ApiException
     */
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        ?int            $campaignId)
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
            dd(1);
            $this->responseErrorGoogleAds($googleAdsException);
        }
    }
}
