<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\ListingCampaignTask;
use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class ListingCampaignAction extends AbstractActions
{
    public function run(Request $request)
    {
        return app(ListingCampaignTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"));
    }
}
