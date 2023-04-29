<?php
namespace App\Containers\AdGroup\Actions;
use App\Containers\AdGroup\Tasks\DeleteAddGroupTask;
use App\Http\Core\AbstractActions;

class DeleteAddGroupAction extends AbstractActions {
    public function run($request, $id){
        return app(DeleteAddGroupTask::class)
            ->run($request->route('googleAdsClient'),env("ACCOUNT_ID", ""),$id);
    }
}
