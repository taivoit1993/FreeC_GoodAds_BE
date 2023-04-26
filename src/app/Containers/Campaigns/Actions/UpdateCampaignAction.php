<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\FindCampaignByIdTask;
use Google\Ads\GoogleAds\V12\Enums\CampaignStatusEnum\CampaignStatus;
use Illuminate\Http\Request;

class UpdateCampaignAction
{
    public function run(Request $request, $id)
    {
        try {
            $googleAdsClient = $request->route("googleAdsClient") ?? null;
            $customerId = env("ACCOUNT_ID", "");
            $campaignId = (int)$id ?? null;
            $campaignName = $request->campaign_name ?? "";
            $status = $request->status ?? CampaignStatus::PAUSED;
            return app(FindCampaignByIdTask::class)
                ->run($googleAdsClient,
                    $customerId,
                    $campaignId,
                    $campaignName,
                    $status);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
