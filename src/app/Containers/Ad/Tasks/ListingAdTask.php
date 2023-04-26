<?php

namespace App\Containers\Ad\Tasks;

use App\Trait\GoogleAdTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;

class ListingAdTask
{
    use GoogleAdTrait;

    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId,
                        ?int            $campaignId)
    {
        try {
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT ad_group.id,'
                . ' ad_group_ad.ad.id, ad_group_ad.ad.name, ad_group_ad.status,'
                . ' ad_group_ad.ad.responsive_search_ad.descriptions,'
                . ' ad_group_ad.ad.responsive_search_ad.headlines,'
                . ' ad_group_ad.ad.responsive_search_ad.path1,'
                . ' ad_group_ad.ad.responsive_search_ad.path2,'
                . ' ad_group_ad.ad.final_urls'
                . ' FROM ad_group_ad';
//        if ($campaignId !== null) {
//            $query .= " WHERE campaign.id = $campaignId";
//        }
            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query);

            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $headLines = self::getListTest($googleAdsRow->getAdGroupAd()->getAd()
                    ->getResponsiveSearchAd()->getHeadLines());
                $descriptions = self::getListTest($googleAdsRow->getAdGroupAd()->getAd()
                    ->getResponsiveSearchAd()->getDescriptions());
                $data [] = [
                    "id" => $googleAdsRow->getAdGroupAd()->getAd()->getId(),
                    "name" => $googleAdsRow->getAdGroupAd()->getAd()->getName(),
                    "status" => AdGroupAdStatus::name($googleAdsRow->getAdGroupAd()->getStatus()),
                    "headLine1" => $headLines[0] ?? "",
                    "headLine2" => $headLines[1] ?? "",
                    "headLine3" => $headLines[2] ?? "",
                    "description1" => $descriptions[0] ?? "",
                    "description2" => $descriptions[1] ?? "",
                    "finalUrls" => $googleAdsRow->getAdGroupAd()->getAd()
                        ->getFinalUrls()
                ];
            }
            return $data;
        } catch (GoogleAdsException $googleAdsException) {
        }
    }
}
