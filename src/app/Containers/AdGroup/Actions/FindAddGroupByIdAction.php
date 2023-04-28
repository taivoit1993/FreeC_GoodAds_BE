<?php

namespace App\Containers\AdGroup\Actions;
use App\Containers\AdGroup\Tasks\FindAddGroudByIdTask;

class FindAddGroupByIdAction{
    public function run($request, $id){
        return app(FindAddGroudByIdTask::class)
            ->run($request->route('googleAdsClient'),env("ACCOUNT_ID", ""),$id);
    }
}
