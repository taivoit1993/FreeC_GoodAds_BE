<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\CreateCampaignTask;
use Carbon\Carbon;
use Exception;
use Google\Ads\GoogleAds\V13\Enums\CampaignStatusEnum\CampaignStatus;
use Illuminate\Http\Request;

class CreateCampaignAction
{
    public function run(Request $request)
    {
        try {
            $googleAdsClient = $request->route("googleAdsClient") ?? null;
            $customerId = env("ACCOUNT_ID", "");
            $amountMicros = $request->amount_micros ?? 0;
            $campaignName = $request->name ?? null;
            $status = $request->status ?? CampaignStatus::PAUSED;
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->format("Ymd") : Carbon::now()->format("Ymd");
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->format("Ymd") : Carbon::now()->format("Ymd");
            return app(CreateCampaignTask::class)
                ->run($googleAdsClient,
                    $customerId,
                    $amountMicros,
                    $campaignName,
                    $status,
                    $startDate,
                    $endDate);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
