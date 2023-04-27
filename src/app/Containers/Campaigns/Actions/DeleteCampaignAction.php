<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\DeleteCampaignTask;
use Exception;
use Illuminate\Http\Request;

class DeleteCampaignAction
{
    public function run(Request $request, string $id)
    {
        try {
            $googleAdsClient = $request->route("googleAdsClient") ?? null;
            $customerId = env("ACCOUNT_ID", "");
            $campaignId = (int)$id ?? null;
            return app(DeleteCampaignTask::class)
                ->run($googleAdsClient, $customerId, $campaignId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
