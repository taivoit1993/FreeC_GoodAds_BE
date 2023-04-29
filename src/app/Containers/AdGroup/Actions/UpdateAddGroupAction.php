<?php

namespace App\Containers\AdGroup\Actions;

use App\Containers\AdGroup\Tasks\UpdateAddGroupTask;
use App\Http\Core\AbstractActions;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;


class UpdateAddGroupAction extends AbstractActions
{
    public function run($request, $id)
    {
        $googleAdsClient = $request->route('googleAdsClient') ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $status = $request->status ?? AdGroupStatus::ENABLED;
        $name = $request->name ?? null;
        $cpcBidMicros = $request->cpc_bid_micros ?? 0;
        return app(UpdateAddGroupTask::class)
            ->run($googleAdsClient, $customerId, $id, $cpcBidMicros, $status, $name);
    }
}
