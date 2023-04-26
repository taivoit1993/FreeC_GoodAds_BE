<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\ListingCampaignTask;
use App\Containers\GoogleAuthentication\Tasks\GenerateGoogleUrlTask;
use Exception;
use Illuminate\Http\Request;

class ListingCampaignAction
{
    public function run(Request $request)
    {
        try {
            return app(ListingCampaignTask::class)
                ->run($request->route("googleAdsClient"),env("ACCOUNT_ID"));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}
