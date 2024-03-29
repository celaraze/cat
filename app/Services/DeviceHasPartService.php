<?php

namespace App\Services;

use App\Models\DeviceHasPart;
use App\Models\Part;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasPartService extends Service
{
    public function __construct(?DeviceHasPart $device_has_part = null)
    {
        $this->model = $device_has_part ?? new DeviceHasPart();
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['creator_id' => 'int', 'status' => 'int'])]
    public function delete(array $data): void
    {
        if ($this->model->getAttribute('deleted_at') == null) {
            try {
                DB::beginTransaction();
                $new_device_has_part = $this->model->replicate();
                $new_device_has_part->save();
                $new_device_has_part->setAttribute('creator_id', $data['creator_id']);
                $new_device_has_part->setAttribute('status', $data['status']);
                $new_device_has_part->save();
                $new_device_has_part->delete();
                /* @var Part $part */
                $part = $this->model->part()->first();
                $part->setAttribute('status', 0);
                $part->save();
                $this->model->delete();
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'device_id' => 'int',
        'part_id' => 'int',
        'creator_id' => 'int',
        'status' => 'int',
    ])]
    public function create(array $data): DeviceHasPart
    {
        $exist = DeviceHasPart::query()
            ->where('device_id', $data['device_id'])
            ->where('part_id', $data['part_id'])
            ->count();
        if ($exist) {
            throw new Exception(__('cat/device_has_part_exist'));
        }
        try {
            DB::beginTransaction();
            $this->model->setAttribute('device_id', $data['device_id']);
            $this->model->setAttribute('part_id', $data['part_id']);
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->setAttribute('status', $data['status']);
            $this->model->save();
            /* @var Part $part */
            $part = $this->model->part()->first();
            $part->setAttribute('status', 1);
            $part->save();
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
