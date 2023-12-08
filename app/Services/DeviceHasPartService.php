<?php

namespace App\Services;

use App\Models\DeviceHasPart;
use App\Models\Part;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasPartService
{
    public DeviceHasPart $device_has_part;

    public function __construct(?DeviceHasPart $device_has_part = null)
    {
        $this->device_has_part = $device_has_part ?? new DeviceHasPart();
    }

    /**
     * 判断是否已经删除的记录.
     */
    public function isDeleted(): bool
    {
        if ($this->device_has_part->getAttribute('deleted_at')) {
            return true;
        }

        return false;
    }

    /**
     * 删除配件管理记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['user_id' => 'int', 'status' => 'int'])]
    public function delete(array $data): void
    {
        try {
            DB::beginTransaction();
            $new_device_has_part = $this->device_has_part->replicate();
            $new_device_has_part->save();
            $new_device_has_part->setAttribute('user_id', $data['user_id']);
            $new_device_has_part->setAttribute('status', $data['status']);
            $new_device_has_part->save();
            $new_device_has_part->delete();
            /* @var Part $part */
            $part = $this->device_has_part->part()->first();
            $part->setAttribute('status', 0);
            $part->save();
            $this->device_has_part->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 设备附属配件.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'device_id' => 'int',
        'part_id' => 'int',
        'user_id' => 'int',
        'status' => 'int',
    ])]
    public function create(array $data): DeviceHasPart
    {
        $exist = DeviceHasPart::query()
            ->where('device_id', $data['device_id'])
            ->where('part_id', $data['part_id'])
            ->count();
        if ($exist) {
            throw new Exception('配件已经附加到此设备');
        }
        try {
            DB::beginTransaction();
            $this->device_has_part->setAttribute('device_id', $data['device_id']);
            $this->device_has_part->setAttribute('part_id', $data['part_id']);
            $this->device_has_part->setAttribute('user_id', $data['user_id']);
            $this->device_has_part->setAttribute('status', $data['status']);
            $this->device_has_part->save();
            /* @var Part $part */
            $part = $this->device_has_part->part()->first();
            $part->setAttribute('status', 1);
            $part->save();
            DB::commit();

            return $this->device_has_part;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
