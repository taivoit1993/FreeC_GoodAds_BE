<?php

namespace App\Containers\Campaigns\Actions;

use App\Containers\Campaigns\Tasks\CreateCampaignTask;
use Exception;
use Illuminate\Http\Request;

class CreateCampaignAction
{
    public function run(Request $request)
    {
        try {
            $googleAdsClient = $request->route("googleAdsClient") ?? null;
            $customerId = env("ACCOUNT_ID", "");
            $amountMicros = $request->amount_micros ?? 0;
            $campaignName = $request->campaign_name ?? null;
            $targetGoogleSearch = $request->target_google_search ?? false;
            $targetSearchNetwork = $request->target_search_network ?? false;
            $targetContentNetwork = $request->target_content_network ?? false;
            $targetPartnerSearchNetwork = $request->target_partner_search_network ?? false;
            return app(CreateCampaignTask::class)
                ->run($googleAdsClient,
                    $customerId,
                    $amountMicros,
                    $campaignName,
                    $targetGoogleSearch,
                    $targetSearchNetwork,
                    $targetContentNetwork,
                    $targetPartnerSearchNetwork);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
