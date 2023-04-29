<?php
namespace App\Containers\Ad\Actions;


use App\Containers\Ad\Tasks\FindAdByIdTask;
use App\Http\Core\AbstractActions;
use Illuminate\Http\Request;

class FindAdByIdAction extends AbstractActions
{
    public function run(Request $request,$id)
    {
        return app(FindAdByIdTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"),$id);
    }
}
