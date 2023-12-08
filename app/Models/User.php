<?php

namespace App\Models;

use App\Services\UserService;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatar = $this->getAttribute('avatar_url');

        return ! $avatar ? '/images/default.jpg' : '/storage/'.$avatar;
    }

    /**
     * 模型到服务.
     */
    public function service(): UserService
    {
        return new UserService($this);
    }

    /**
     * 一对多，用户有很多设备使用记录.
     */
    public function deviceHasUsers(): HasMany
    {
        return $this->hasMany(DeviceHasUser::class, 'user_id', 'id');
    }

    /**
     * 一对多，用户有很多申请的表单.
     */
    public function applicantForms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'applicant_user_id', 'id');
    }

    /**
     * 一对多，用户有很多需要审批的表单.
     */
    public function approvalForms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'approve_user_id', 'id');
    }

    /**
     * 一对多，用户有很多需要审批的流程节点.
     */
    public function approvalNodes(): HasMany
    {
        return $this->hasMany(FlowHasNode::class, 'user_id', 'id');
    }
}
