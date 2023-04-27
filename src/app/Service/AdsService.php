<?php

namespace App\Service;

use App\Trait\GoogleAdTrait;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Common\ResponsiveSearchAdInfo;
use Google\Ads\GoogleAds\V13\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V13\Enums\ServedAssetFieldTypeEnum\ServedAssetFieldType;
use Google\Ads\GoogleAds\V13\Resources\Ad;
use Google\Ads\GoogleAds\V13\Resources\AdGroupAd;
use Google\Ads\GoogleAds\V13\Services\AdGroupAdOperation;
use Google\Exception;

class AdsService
{
    use GoogleAdTrait;

    public function createAds(GoogleAdsClient $googleAdsClient,
                              int             $customerId,
                              int             $adGroupId)
    {
        $ad = new Ad([
            'responsive_search_ad' => new ResponsiveSearchAdInfo([
                'headlines' => [
                    // Sets a pinning to always choose this asset for HEADLINE_1. Pinning is
                    // optional; if no pinning is set, then headlines and descriptions will be
                    // rotated and the ones that perform best will be used more often.
                    self::createAdTextAsset(
                        'Headlines 1',
                        ServedAssetFieldType::HEADLINE_1
                    ),
                    self::createAdTextAsset('Best Space Cruise Line'),
                    self::createAdTextAsset('Experience the Stars')
                ],
                'descriptions' => [
                    self::createAdTextAsset('Buy your tickets now'),
                    self::createAdTextAsset('Visit the Red Planet')
                ],
                'path1' => 'all-inclusive',
                'path2' => 'deals'
            ]),
            'final_urls' => ['http://www.example.com'],
        ]);

        // Creates an ad group ad to hold the above ad.
        $adGroupAd = new AdGroupAd([
            'ad_group' => ResourceNames::forAdGroup($customerId, $adGroupId),
            'status' => AdGroupAdStatus::PAUSED,
            'ad' => $ad
        ]);

        // Creates an ad group ad operation.
        $adGroupAdOperation = new AdGroupAdOperation();
        $adGroupAdOperation->setCreate($adGroupAd);

        // Issues a mutate request to add the ad group ad.
        $adGroupAdServiceClient = $googleAdsClient->getAdGroupAdServiceClient();
        $response = $adGroupAdServiceClient->mutateAdGroupAds($customerId, [$adGroupAdOperation]);

        $createdAdGroupAdResourceName = $response->getResults()[0]->getResourceName();
        return $createdAdGroupAdResourceName;
    }

    public function listingAds(GoogleAdsClient $googleAdsClient,
                               int             $customerId)
    {
        try{
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT ad_group.id,'
                .' ad_group_ad.ad.id, ad_group_ad.ad.name,'
                .' ad_group_ad.ad.responsive_search_ad.descriptions,'
                .' ad_group_ad.ad.responsive_search_ad.headlines,'
                .' ad_group_ad.ad.responsive_search_ad.path1,'
                .' ad_group_ad.ad.responsive_search_ad.path2,'
                .' ad_group_ad.ad.final_urls'
                .' FROM ad_group_ad';
//        if ($campaignId !== null) {
//            $query .= " WHERE campaign.id = $campaignId";
//        }
            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query);
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $headLines = $googleAdsRow->getAdGroupAd()
                    ->getAd()->getResponsiveSearchAd()->getHeadLines();
                $hl = [];
                foreach ($headLines->getIterator() as $headLine){
                    $hl [] = $headLine->getText();
                }

                $descriptions = $googleAdsRow->getAdGroupAd()
                        ->getAd()->getResponsiveSearchAd()->getDescriptions();
                $ds = [];
                foreach ($descriptions->getIterator() as $description){
                    $ds [] = $description->getText();
                }
                $data [] = [
                    "id" => $googleAdsRow->getAdGroupAd()->getAd()->getId(),
                    "name" => $googleAdsRow->getAdGroupAd()->getAd()->getName(),
                    'headlines' => $hl,
                    'descriptions' => $ds,
                    "finalUrls" => $googleAdsRow->getAdGroupAd()->getAd()
                        ->getFinalUrls()
                ];
            }
            return $data;
        } catch (GoogleAdsException $googleAdsException) {
        }
    }
}
