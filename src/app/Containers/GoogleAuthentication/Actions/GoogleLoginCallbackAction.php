<?php
namespace App\Containers\GoogleAuthentication\Actions;
use App\Containers\GoogleAuthentication\Tasks\GoogleLoginCallbackTask;
use Exception;

class GoogleLoginCallbackAction{
    public function run()
    {
        try {
            return app(GoogleLoginCallbackTask::class)
                    ->run();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}
