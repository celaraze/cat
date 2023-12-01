<?php

namespace App\Models;

use App\Services\SettingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * 模型到服务.
     */
    public function service(): SettingService
    {
        return new SettingService();
    }
}
