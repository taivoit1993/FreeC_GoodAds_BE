<?php

namespace App\Containers\Ad\Tasks;

use App\Trait\GoogleAdTrait;
use App\Trait\ResponseTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\V13\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;

class ListingAdTask
{
    use GoogleAdTrait, ResponseTrait;

    public function run(GoogleAdsClient $googleAdsClient,
                        int             $customerId)
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
                . ' FROM ad_group_ad ORDER BY ad_group_ad.ad.id desc';

            // Issues a search request
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
                    "ad_group_id" => $googleAdsRow->getAdGroup()->getId(),
                    "name" => $googleAdsRow->getAdGroupAd()->getAd()->getName(),
                    "status" => AdGroupAdStatus::name($googleAdsRow->getAdGroupAd()->getStatus()),
                    "head_line_1" => $headLines[0] ?? "",
                    "head_line_2" => $headLines[1] ?? "",
                    "head_line_3" => $headLines[2] ?? "",
                    "description_1" => $descriptions[0] ?? "",
                    "description_2" => $descriptions[1] ?? "",
                    "final_urls" => $googleAdsRow->getAdGroupAd()->getAd()
                        ->getFinalUrls()
                ];
            }
            return $data;
        } catch (GoogleAdsException $googleAdsException) {
            $this->responseAdsError($googleAdsException);
        }
    }
}
