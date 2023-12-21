<?php

namespace App\Services;

use App\Models\DeviceHasSoftware;
use App\Models\Part;
use App\Models\Software;
use App\Traits\Services\HasFootprint;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasSoftwareService
{
    use HasFootprint;

    public DeviceHasSoftware $model;

    public function __construct(?DeviceHasSoftware $device_has_software = null)
    {
        $this->model = $device_has_software ?? new DeviceHasSoftware();
    }

    /**
     * 删除软件管理记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['creator_id' => 'int', 'status' => 'string'])]
    public function delete(array $data): void
    {
        if ($this->model->getAttribute('deleted_at') == null) {
            try {
                DB::beginTransaction();
                $new_device_has_software = $this->model->replicate();
                $new_device_has_software->save();
                $new_device_has_software->setAttribute('creator_id', $data['creator_id']);
                $new_device_has_software->setAttribute('status', $data['status']);
                $new_device_has_software->save();
                $new_device_has_software->delete();
                /* @var Software $software */
                $software = $this->model->software()->first();
                $this->model->delete();
                if (!$software->hasSoftware()->count()) {
                    $software->setAttribute('status', 0);
                    $software->save();
                }
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
    }

    /**
     * 判断是否已经删除的记录.
     */
    public function isDeleted(): bool
    {
        if ($this->model->getAttribute('deleted_at')) {
            return true;
        }

        return false;
    }

    /**
     * 设备附属软件.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'device_id' => 'int',
        'software_id' => 'int',
        'creator_id' => 'int',
        'status' => 'string',
    ])]
    public function create(array $data): DeviceHasSoftware
    {
        $exist = DeviceHasSoftware::query()
            ->where('device_id', $data['device_id'])
            ->where('software_id', $data['software_id'])
            ->count();
        if ($exist) {
            throw new Exception(__('cat.device_has_software_exist'));
        }
        $software = Software::query()->where('id', $data['software_id'])->first();
        if (!$software) {
            throw new Exception(__('cat.software_not_found'));
        }
        /* @var Software $software */
        $max_license_count = $software->getAttribute('max_license_count');
        if ($max_license_count != 0 && $software->usedCount() >= $max_license_count) {
            throw new Exception(__('cat.software_license_exhausted'));
        }

        try {
            DB::beginTransaction();
            $this->model->setAttribute('device_id', $data['device_id']);
            $this->model->setAttribute('software_id', $data['software_id']);
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->setAttribute('status', $data['status']);
            $this->model->save();
            /* @var Part $part */
            $software = $this->model->software()->first();
            $software->setAttribute('status', 1);
            $software->save();
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
