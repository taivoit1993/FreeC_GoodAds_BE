<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\FindCampaignByIdTask;
use Exception;
use Illuminate\Http\Request;

class FindCampaignByIdAction
{
    public function run(Request $request, string $id)
    {
        try {
            $googleAdsClient = $request->route("googleAdsClient") ?? null;
            $customerId = env("ACCOUNT_ID", "");
            $campaignId = (int) $id ?? null;
            return app(FindCampaignByIdTask::class)
                ->run($googleAdsClient, $customerId, $campaignId);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
