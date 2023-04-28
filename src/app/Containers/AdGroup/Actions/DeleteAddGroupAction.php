<?php
namespace App\Containers\AdGroup\Actions;
use App\Containers\AdGroup\Tasks\DeleteAddGroupTask;

class DeleteAddGroupAction{
    public function run($request, $id){
        return app(DeleteAddGroupTask::class)
            ->run($request->route('googleAdsClient'),env("ACCOUNT_ID", ""),$id);
    }
}
