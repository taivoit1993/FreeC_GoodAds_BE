<?php

namespace App\Containers\Ad\Actions;


use App\Containers\Ad\Tasks\ListingAdTask;
use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class ListingAdAction extends AbstractActions
{
    public function run(Request $request)
    {
        return app(ListingAdTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"));
    }
}
