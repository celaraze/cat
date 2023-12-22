<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryHasTrack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * 获取全部盘点任务.
     */
    public function inventory(): JsonResponse
    {
        $inventories = Inventory::query()->get();

        return response()
            ->json([
                'status' => 200,
                'data' => $inventories->toArray(),
            ]);
    }

    /**
     * 盘点.
     *
     * @return JsonResponse|void
     */
    public function check(Request $request)
    {
        $inventory_id = $request->get('inventory_id');
        $asset_number = $request->get('asset_number');
        $check = $request->get('check');
        $user = $request->user();
        if (! $user->can('check_inventory')) {
            return response()
                ->json([
                    'status' => 403,
                    'message' => __('cat.auth.unauthorized'),
                ]);
        }
        if ($check != 1 && $check != 2) {
            return response()
                ->json([
                    'status' => 403,
                    'message' => __('cat.auth.unauthorized'),
                ]);
        }
        $inventory_has_tracks = InventoryHasTrack::query()
            ->where('inventory_id', $inventory_id)
            ->where('asset_number', $asset_number)
            ->first();
        if (! $inventory_has_tracks) {
            return response()
                ->json([
                    'status' => 404,
                    'message' => __('cat.inventory_has_track.not_found'),
                ]);
        }
        if ($inventory_has_tracks->getAttribute('check') != 0) {
            return Response()
                ->json([
                    'status' => 404,
                    'message' => __('cat.inventory_has_track.checked'),
                ]);
        }
        $inventory_has_tracks->setAttribute('check', $check);
        $inventory_has_tracks->setAttribute('user_id', $user->getKey());
        if ($inventory_has_tracks->save()) {
            return response()
                ->json([
                    'status' => 200,
                    'message' => __('cat.inventory_has_track.check_success'),
                ]);
        }
    }
}
