<?php

namespace App\Containers\Ad\Actions;

use App\Containers\Ad\Tasks\DeleteAddTask;
use App\Http\Core\AbstractActions;

class DeleteAdAction extends AbstractActions
{
    public function run($request, $id)
    {
        $googleAdsClient = $request->route('googleAdsClient') ?? null;
        $customerId = env("ACCOUNT_ID", "");
        $addGroupId = $request->ad_group_id ?? null;
        return app(DeleteAddTask::class)
            ->run($googleAdsClient, $customerId, $addGroupId, $id);
    }
}
