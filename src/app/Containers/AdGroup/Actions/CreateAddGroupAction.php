<?php

namespace App\Containers\AdGroup\Actions;

use App\Containers\AdGroup\Tasks\CreateAddGroupTask;

use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class CreateAddGroupAction extends AbstractActions
{
    public function run(Request $request)
    {
        $googleAdsClient = $request->route('googleAdsClient') ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $campaignId = $request->campaign_id ?? null;
        $name = $request->name ?? null;
        $cpcBidMicros = $request->cpc_bid_micros ?? 0;
        return app(CreateAddGroupTask::class)
            ->run($googleAdsClient, $customerId, $campaignId, $name, $cpcBidMicros);
    }
}
