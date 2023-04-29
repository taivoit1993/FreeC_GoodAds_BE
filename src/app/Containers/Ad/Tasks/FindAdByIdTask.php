<?php

namespace App\Containers\Ad\Tasks;

use App\Http\Core\AbstractTasks;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;

class FindAdByIdTask extends AbstractTasks
{
    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        int             $adId)
    {
        try{
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT ad_group.id,'
                . ' ad_group_ad.ad.id, ad_group_ad.ad.name, ad_group_ad.status,'
                . ' ad_group_ad.ad.responsive_search_ad.descriptions,'
                . ' ad_group_ad.ad.responsive_search_ad.headlines,'
                . ' ad_group_ad.ad.responsive_search_ad.path1,'
                . ' ad_group_ad.ad.responsive_search_ad.path2,'
                . ' ad_group_ad.ad.final_urls'
                . ' FROM ad_group_ad WHERE ad_group_ad.ad.id = ' . $adId;

            $response =
                $googleAdsServiceClient->search($customerId, $query)
                    ->getIterator()
                    ->current();

            $headLines = self::getListTest($response->getAdGroupAd()->getAd()
                ->getResponsiveSearchAd()->getHeadLines());
            $descriptions = self::getListTest($response->getAdGroupAd()->getAd()
                ->getResponsiveSearchAd()->getDescriptions());
            return [
                "id" => $response->getAdGroupAd()->getAd()->getId(),
                "ad_group_id" => $response->getAdGroup()->getId(),
                "name" => $response->getAdGroupAd()->getAd()->getName(),
                "status" => $response->getAdGroupAd()->getStatus(),
                "head_line_1" => $headLines[0] ?? "",
                "head_line_2" => $headLines[1] ?? "",
                "head_line_3" => $headLines[2] ?? "",
                "description_1" => $descriptions[0] ?? "",
                "description_2" => $descriptions[1] ?? "",
                "final_urls" => $response->getAdGroupAd()->getAd()
                    ->getFinalUrls()
            ];
        }catch(GoogleAdsException $googleAdsException){
            $this->responseAdsError($googleAdsException);
        }

    }
}
