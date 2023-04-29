<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\DeleteCampaignTask;
use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class DeleteCampaignAction extends AbstractActions
{
    public function run(Request $request, string $id)
    {
        $googleAdsClient = $request->route("googleAdsClient") ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $campaignId = (int)$id ?? null;
        return app(DeleteCampaignTask::class)
            ->run($googleAdsClient, $customerId, $campaignId);
    }
}
