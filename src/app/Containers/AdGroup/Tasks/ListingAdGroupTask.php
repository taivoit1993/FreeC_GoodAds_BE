<?php

namespace App\Containers\AdGroup\Tasks;

use App\Http\Core\AbstractTasks;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;

class ListingAdGroupTask extends AbstractTasks
{

    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        ?int            $campaignId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT campaign.id, ad_group.id, ad_group.name,'
                .' ad_group.cpc_bid_micros, ad_group.status FROM ad_group '
            .' WHERE ad_group.status != '. AdGroupStatus::name(AdGroupStatus::REMOVED)
            .' ORDER BY ad_group.id desc';
            if ($campaignId !== null) {
                $query .= " WHERE campaign.id = $campaignId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query);
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $data [] = [
                    "id" => $googleAdsRow->getAdGroup()->getId(),
                    "name" => $googleAdsRow->getAdGroup()->getName(),
                    'cpc_bid_micros' => $googleAdsRow->getAdGroup()->getCpcBidMicros(),
                    'status' => AdGroupStatus::name($googleAdsRow->getAdGroup()->getStatus()),
                    "campaign_id" => $googleAdsRow->getCampaign()->getId()
                ];
            }
            return $data;
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
