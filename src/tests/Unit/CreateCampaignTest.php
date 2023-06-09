<?php

namespace Tests\Unit;

use App\Containers\Campaigns\Tasks\CreateCampaignTask;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;

use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use PHPUnit\Framework\TestCase;

class CreateCampaignTest extends TestCase
{
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
     * @dataProvider provideFakeCreateCampaign
     */
    public function testStore(int    $amountMicros,
                              string $campaignName,
                               $status,
                              string $startDate,
                              string $endDate)
    {
        $campaign = app(CreateCampaignTask::class)
            ->run($this->googleAdsClient, (int)$this->customerId, $amountMicros,
                $campaignName,
                $status,
                $startDate,
                $endDate);
        //Check result is string resource
        $this->assertEquals(is_string($campaign), true);
    }

    public static function provideFakeCreateCampaign()
    {
        return [
            [
                50000,
                "unit test create campaign " .rand(). time(),
                CampaignStatus::PAUSED,
                date('Ymd', strtotime('+1 day')),
                date('Ymd', strtotime('+1 month'))
            ],
            [
                100000,
                "unit test create campaign " .rand(). time(),
                CampaignStatus::PAUSED,
                date('Ymd', strtotime('+1 day')),
                date('Ymd', strtotime('+1 month'))
            ],
        ];
    }
}
