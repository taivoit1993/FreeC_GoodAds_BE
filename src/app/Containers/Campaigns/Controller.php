<?php
namespace App\Containers\Campaigns;
use App\Containers\Campaigns\Actions\CreateCampaignAction;
use App\Containers\Campaigns\Actions\DeleteCampaignAction;
use App\Containers\Campaigns\Actions\FindCampaignByIdAction;
use App\Containers\Campaigns\Actions\ListingCampaignAction;
use App\Containers\Campaigns\Actions\UpdateCampaignAction;
use App\Http\Resources\CampaignResource;
use App\Service\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $campaigns = app(ListingCampaignAction::class)->run($request);
        return new CampaignResource($campaigns);
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
        return app(CreateCampaignAction::class)->run($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        $campaign = app(FindCampaignByIdAction::class)->run($request,$id);
        return new CampaignResource($campaign);
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
        return app(UpdateCampaignAction::class)->run($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //
        app(DeleteCampaignAction::class)->run($request, $id);
        return response()->noContent();
//        app(CampaignService::class)->removeCampaign($this->googleAdsClient,"9513370025",$id);
//        return response()->json(["message"=>"delete success"]);
    }
}
