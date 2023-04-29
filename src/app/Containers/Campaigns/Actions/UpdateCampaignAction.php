<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\UpdateCampaignTask;
use App\Http\Core\AbstractActions;
use Google\Ads\GoogleAds\V12\Enums\CampaignStatusEnum\CampaignStatus;
use Illuminate\Http\Request;

class UpdateCampaignAction extends AbstractActions
{
    public function run(Request $request, $id)
    {
        $googleAdsClient = $request->route("googleAdsClient") ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $campaignId = (int)$id ?? null;
        $status = $request->status ?? CampaignStatus::PAUSED;
        $name = $request->name ?? "";
        return app(UpdateCampaignTask::class)
            ->run($googleAdsClient,
                $customerId,
                $campaignId,
                $status,
                $name);
    }
}
