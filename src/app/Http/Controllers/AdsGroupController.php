<?php

namespace App\Http\Controllers;

use App\Service\AdsGroupService;
use Illuminate\Http\Request;

class AdsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return app(AdsGroupService::class)
            ->listingAdsGroup($this->googleAdsClient,"9513370025",20,"20029051604");
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
        return app(AdsGroupService::class)
            ->createAdsGroup($this->googleAdsClient,"9513370025","20029051604");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        return app(AdsGroupService::class)
            ->updateAdsGroup($this->googleAdsClient,"9513370025",$id,500000);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        app(AdsGroupService::class)
            ->removeAdGroup($this->googleAdsClient,"9513370025",$id);
        return response()->noContent();
    }
}
