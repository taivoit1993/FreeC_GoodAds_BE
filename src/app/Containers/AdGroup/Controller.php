<?php

namespace App\Containers\AdGroup;

use App\Containers\AdGroup\Actions\CreateAddGroupAction;
use App\Containers\AdGroup\Actions\DeleteAddGroupAction;
use App\Containers\AdGroup\Actions\FindAddGroupByIdAction;
use App\Containers\AdGroup\Actions\ListingAdGroupAction;
use App\Containers\AdGroup\Actions\UpdateAddGroupAction;
use App\Http\Resources\AdGroupResource;
use App\Service\AdsGroupService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $adGroups = app(ListingAdGroupAction::class)
            ->run($request);
        return new AdGroupResource($adGroups);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return app(CreateAddGroupAction::class)->run($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        $adGroup = app(FindAddGroupByIdAction::class)->run($request,$id);
        return new AdGroupResource($adGroup);
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
        return app(UpdateAddGroupAction::class)->run($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        //
        app(DeleteAddGroupAction::class)->run($request,$id);
        return response()->noContent();
    }
}
