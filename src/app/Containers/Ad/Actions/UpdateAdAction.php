<?php

namespace App\Containers\Ad\Actions;

use App\Containers\Ad\Tasks\UpdateAdTask;
use App\Http\Core\AbstractActions;
use Google\Ads\GoogleAds\V13\Enums\AdGroupStatusEnum\AdGroupStatus;
use Illuminate\Http\Request;

class UpdateAdAction extends AbstractActions
{
    public function run(Request $request, $id)
    {
        $googleAdsClient = $request->route('googleAdsClient') ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $headLine1 = $request->head_line_1 ?? "";
        $headLine2 = $request->head_line_2 ?? "";
        $headLine3 = $request->head_line_3 ?? "";
        $description1 = $request->description_1 ?? "";
        $description2 = $request->description_2 ?? "";
        $status = $request->status ?? AdGroupStatus::PAUSED;
        $adGroupId = $request->ad_group_id ?? null;
        $ad = app(UpdateAdTask::class)
            ->run($googleAdsClient,
                $customerId,
                $id,
                $headLine1,
                $headLine2,
                $headLine3,
                $description1,
                $description2
            );
//        app(UpdateStatusAdGroupAdTask::class)
//            ->run($googleAdsClient,
//                $customerId,
//                $adGroupId,
//                $id,
//                $status);
        return $ad;
    }
}
