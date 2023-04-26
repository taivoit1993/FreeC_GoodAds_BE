<?php

namespace App\Containers\Ad\Actions;


use App\Containers\Ad\Tasks\ListingAdTask;
use Illuminate\Http\Request;

class ListingAdAction
{
    public function run(Request $request)
    {
        $campaignId = $request->campaign_id ?? null;
        return app(ListingAdTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"), $campaignId);
    }
}
