<?php

namespace App\Containers\Ad;


use App\Containers\Ad\Actions\CreateAdAction;
use App\Containers\Ad\Actions\DeleteAdAction;
use App\Containers\Ad\Actions\FindAdByIdAction;
use App\Containers\Ad\Actions\ListingAdAction;
use App\Containers\Ad\Actions\UpdateAdAction;
use App\Http\Resources\AdResource;
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
        $ads = app(ListingAdAction::class)
            ->run($request);

        return new AdResource($ads);
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
        return app(CreateAdAction::class)->run($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        //
        $ad = app(FindAdByIdAction::class)->run($request, $id);
        return new AdResource($ad);
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
        return app(UpdateAdAction::class)->run($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        //
        app(DeleteAdAction::class)->run($request,$id);
        return response()->noContent();
    }
}
