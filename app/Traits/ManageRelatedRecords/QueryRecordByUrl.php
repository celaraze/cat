<?php

namespace App\Traits\ManageRelatedRecords;

use App\Utils\UrlUtil;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait QueryRecordByUrl
{
    /**
     * 通过 URL 获取模型.
     *
     * @throws Exception
     */
    public static function queryRecord(): ?Model
    {
        $model_id = UrlUtil::getRecordId(request()->getRequestUri());

        return static::getResource()::getModel()::query()->where('id', $model_id)->first();
    }
}
