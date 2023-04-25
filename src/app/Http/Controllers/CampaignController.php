<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Service\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $campaign = app(CampaignService::class)->listingCampaign($this->googleAdsClient,"9513370025");
//        return $campaign;
        return new CampaignResource($campaign);
    }

    /**
     *
     */
    public function create(Request $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return app(CampaignService::class)->createCampaign($this->googleAdsClient,
        $request->customer_id,
            $request->amount_micros,
            $request->campaign_name,
            $request->target_google_search,
            $request->target_search_network,
            $request->target_content_network,
            $request->target_partner_search_network);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return app(CampaignService::class)->getDetailCampaign($this->googleAdsClient,"9513370025",$id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        app(CampaignService::class)->updateCampaign($this->googleAdsClient,"9513370025",$id);
        return response()->json(["message"=>"update success"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        app(CampaignService::class)->removeCampaign($this->googleAdsClient,"9513370025",$id);
        return response()->json(["message"=>"delete success"]);
    }
}
