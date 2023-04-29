<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\FindCampaignByIdTask;
use App\Http\Core\AbstractActions;
use Exception;
use Illuminate\Http\Request;

class FindCampaignByIdAction extends AbstractActions
{
    public function run(Request $request, string $id)
    {
        $googleAdsClient = $request->route("googleAdsClient") ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $campaignId = (int)$id ?? null;
        return app(FindCampaignByIdTask::class)
            ->run($googleAdsClient, $customerId, $campaignId);
    }
}
