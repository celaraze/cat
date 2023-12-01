<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    use HasFactory;

    /**
     * 一对多，导入有多个失败导入行.
     */
    public function failedImportRows(): HasMany
    {
        return $this->hasMany(FailedImportRow::class, 'import_id', 'id');
    }

    /**
     * 一对一，导入有一个执行用户.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
