<?php
namespace App\Containers\AdGroup\Tasks;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;

class FindAddGroudByIdTask
{
    use ResponseTrait;

    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adGroupId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT campaign.id, ad_group.id, ad_group.name,'
                . ' ad_group.cpc_bid_micros, ad_group.status '
                . ' FROM ad_group where ad_group.id = ' . $adGroupId . ' limit 1';

            $response =
                $googleAdsServiceClient->search($customerId, $query)
                    ->getIterator()
                    ->current();

            return [
                "id" => $response->getAdGroup()->getId(),
                "name" => $response->getAdGroup()->getName(),
                'cpc_bid_micros' => $response->getAdGroup()->getCpcBidMicros(),
                'status' => $response->getAdGroup()->getStatus(),
                "campaign_id" => $response->getCampaign()->getId()
            ];
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
