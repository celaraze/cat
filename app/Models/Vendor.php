<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对多，厂商有多个联系人.
     *
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(VendorHasContact::class, 'vendor_id', 'id');
    }
}
