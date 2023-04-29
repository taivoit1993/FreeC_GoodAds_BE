<?php

namespace Tests\Unit;

use App\Containers\Ad\Tasks\CreateAdTask;
use App\Containers\Campaigns\Tasks\CreateCampaignTask;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V13\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;

use PHPUnit\Framework\TestCase;

class CreateAdTest extends TestCase
{
    public static function provideFakeCreateAd()
    {
        return[
            [147941824545,"HeadLine Test Ad 1","HeadLine Test Ad2","Healine test Ad3",
                "Description 1","Description 2",AdGroupAdStatus::PAUSED]
        ];
    }

    /**
     * A basic unit test example.
     */

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->googleAdsClient = (new GoogleAdsClientBuilder())
            ->withDeveloperToken(env('DEVELOP_TOKEN', ""))
            ->withLoginCustomerId(env('LOGIN_CUSTOMER_ID', ""))
            ->withOAuth2Credential((new OAuth2TokenBuilder())
                ->withClientId(env('GOOGLE_CLIENT_ID', ""))
                ->withClientSecret(env('GOOGLE_CLIENT_SECRET', ""))
                ->withRefreshToken("1//0ebYoXACvgu6uCgYIARAAGA4SNwF-L9IrdS2RCoNOB26ogPfd-gpNcb4z4TIIk64w7dNb0cYPuGnoVEJSfxpRSzMpoM0SFu9wMlo")
                ->build())
            ->build();
        $this->customerId = env("ACCOUNT_ID");

    }
    /**
     * @dataProvider provideFakeCreateAd
     */
    public function testStore(int             $adGroupId,
                              string          $headLine1,
                              string          $headLine2,
                              string          $headLine3,
                              string          $description1,
                              string          $description2,
                              int             $status)
    {
        $campaign = app(CreateAdTask::class)
            ->run($this->googleAdsClient, (int)$this->customerId,
                $adGroupId,
                $headLine1,
                $headLine2,
                $headLine3,
                $description1,
                $description2,
                $status);
        //Check result is string resource
        $this->assertEquals(is_string($campaign), true);
    }

}