<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Part;
use App\Models\Software;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    }

    /**
     * 通过资产编号查询资产.
     */
    public function show(string $asset_number): JsonResponse
    {
        $device = Device::query()->where('asset_number', $asset_number)->first();
        if ($device) {
            $device->parts;
            $device->software;
            $device->brand;
            $device->category;

            return response()->json([
                'data' => $device,
            ]);
        }

        $part = Part::query()->where('asset_number', $asset_number)->first();
        if ($part) {
            $part->devices;
            $part->brand;
            $part->category;

            return response()->json([
                'data' => $part,
            ]);
        }

        $software = Software::query()->where('asset_number', $asset_number)->first();
        if ($software) {
            $software->devices;
            $software->brand;
            $software->category;

            return response()->json([
                'data' => $software,
            ]);
        }

        return response()->json([
            'message' => __('cat.asset_not_found'),
        ], 404);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
