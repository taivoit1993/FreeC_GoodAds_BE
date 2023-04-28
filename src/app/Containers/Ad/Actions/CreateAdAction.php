<?php

namespace App\Containers\Ad\Actions;

use App\Containers\Ad\Tasks\CreateAdTask;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;


class CreateAdAction
{
    public function run($request)
    {
        $googleAdsClient = $request->route('googleAdsClient') ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $addGroupId = $request->ad_group_id ?? null;
        $headLine1 = $request->head_line_1 ?? "";
        $headLine2 = $request->head_line_2 ?? "";
        $headLine3 = $request->head_line_3 ?? "";
        $description1 = $request->description_1 ?? "";
        $description2 = $request->description_2 ?? "";
        $status = $request->status ?? AdGroupStatus::PAUSED;
        return app(CreateAdTask::class)
            ->run($googleAdsClient,
                $customerId,
                $addGroupId,
                $headLine1,
                $headLine2,
                $headLine3,
                $description1,
                $description2,
                $status);
    }
}
