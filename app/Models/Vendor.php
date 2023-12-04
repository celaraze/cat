<?php

namespace App\Models;

use App\Services\VendorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对多，厂商有多个联系人.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(VendorHasContact::class, 'vendor_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): VendorService
    {
        return new VendorService($this);
    }
}
