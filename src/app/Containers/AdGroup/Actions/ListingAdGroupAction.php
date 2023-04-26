<?php

namespace App\Containers\AdGroup\Actions;

use App\Containers\AdGroup\Tasks\ListingAdGroupTask;
use Illuminate\Http\Request;

class ListingAdGroupAction
{
    public function run(Request $request)
    {
        $campaignId = $request->campaign_id ?? null;
        return app(ListingAdGroupTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"), $campaignId);
    }
}
