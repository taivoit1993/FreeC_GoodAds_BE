<?php

namespace App\Containers\GoogleAuthentication\Actions;

use App\Containers\GoogleAuthentication\Tasks\GenerateGoogleUrlTask;
use Exception;

class GenerateGoogleUrlAction
{
    public function run($scopes, $params)
    {
        try {
            return app(GenerateGoogleUrlTask::class)
                ->run($scopes, $params);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}
