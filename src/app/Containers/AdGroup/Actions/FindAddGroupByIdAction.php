<?php

namespace App\Containers\AdGroup\Actions;
use App\Containers\AdGroup\Tasks\FindAddGroudByIdTask;
use App\Http\Core\AbstractActions;

class FindAddGroupByIdAction extends AbstractActions {
    public function run($request, $id){
        return app(FindAddGroudByIdTask::class)
            ->run($request->route('googleAdsClient'),env("ACCOUNT_ID", ""),$id);
    }
}
