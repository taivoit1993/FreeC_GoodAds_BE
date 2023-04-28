<?php
namespace App\Containers\Ad\Actions;


use App\Containers\Ad\Tasks\FindAdByIdTask;
use Illuminate\Http\Request;

class FindAdByIdAction{
    public function run(Request $request,$id)
    {
        return app(FindAdByIdTask::class)
            ->run($request->route("googleAdsClient"), env("ACCOUNT_ID"),$id);
    }
}
