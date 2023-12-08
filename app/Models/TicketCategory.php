<?php

namespace App\Models;

use App\Services\TicketCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 模型到服务.
     */
    public function service(): TicketCategoryService
    {
        return new TicketCategoryService($this);
    }
}
