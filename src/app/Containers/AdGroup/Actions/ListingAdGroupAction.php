<?php

namespace App\Containers\AdGroup\Actions;

use App\Containers\AdGroup\Tasks\ListingAdGroupTask;
use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class ListingAdGroupAction extends AbstractActions
{
    public function run(Request $request)
    {
        $campaignId = $request->campaign_id ?? null;
        return app(ListingAdGroupTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"), $campaignId);
    }
}
