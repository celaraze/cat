<?php

namespace App\Providers;

use App\Models\AssetNumberRule;
use App\Models\Brand;
use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceHasPart;
use App\Models\DeviceHasSecret;
use App\Models\DeviceHasSoftware;
use App\Models\DeviceHasUser;
use App\Models\Flow;
use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use App\Models\Inventory;
use App\Models\InventoryHasTrack;
use App\Models\Organization;
use App\Models\OrganizationHasUser;
use App\Models\Part;
use App\Models\PartCategory;
use App\Models\Role;
use App\Models\Secret;
use App\Models\Setting;
use App\Models\Software;
use App\Models\SoftwareCategory;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketHasTrack;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorHasContact;
use App\Observers\AssetNumberRuleObserver;
use App\Observers\BrandObserver;
use App\Observers\DeviceCategoryObserver;
use App\Observers\DeviceHasPartObserver;
use App\Observers\DeviceHasSecretObserver;
use App\Observers\DeviceHasSoftwareObserver;
use App\Observers\DeviceHasUserObserver;
use App\Observers\DeviceObserver;
use App\Observers\FlowHasFormObserver;
use App\Observers\FlowHasNodeObserver;
use App\Observers\FlowObserver;
use App\Observers\InventoryHasTrackObserver;
use App\Observers\InventoryObserver;
use App\Observers\OrganizationHasUserObserver;
use App\Observers\OrganizationObserver;
use App\Observers\PartCategoryObserver;
use App\Observers\PartObserver;
use App\Observers\RoleObserver;
use App\Observers\SecretObserver;
use App\Observers\SettingObserver;
use App\Observers\SoftwareCategoryObserver;
use App\Observers\SoftwareObserver;
use App\Observers\TicketCategoryObserver;
use App\Observers\TicketHasTrackObserver;
use App\Observers\TicketObserver;
use App\Observers\UserObserver;
use App\Observers\VendorHasContactObserver;
use App\Observers\VendorObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // filament 需要关闭模型防护
        Model::unguard();

        AssetNumberRule::observe(AssetNumberRuleObserver::class);
        // AssetNumberTrack::observe(AssetNumberRuleObserver::class);
        Brand::observe(BrandObserver::class);
        DeviceCategory::observe(DeviceCategoryObserver::class);
        DeviceHasPart::observe(DeviceHasPartObserver::class);
        DeviceHasSoftware::observe(DeviceHasSoftwareObserver::class);
        DeviceHasUser::observe(DeviceHasUserObserver::class);
        Device::observe(DeviceObserver::class);
        FlowHasForm::observe(FlowHasFormObserver::class);
        FlowHasNode::observe(FlowHasNodeObserver::class);
        Flow::observe(FlowObserver::class);
        InventoryHasTrack::observe(InventoryHasTrackObserver::class);
        Inventory::observe(InventoryObserver::class);
        OrganizationHasUser::observe(OrganizationHasUserObserver::class);
        Organization::observe(OrganizationObserver::class);
        PartCategory::observe(PartCategoryObserver::class);
        Part::observe(PartObserver::class);
        Role::observe(RoleObserver::class);
        Setting::observe(SettingObserver::class);
        SoftwareCategory::observe(SoftwareCategoryObserver::class);
        Software::observe(SoftwareObserver::class);
        TicketCategory::observe(TicketCategoryObserver::class);
        TicketHasTrack::observe(TicketHasTrackObserver::class);
        Ticket::observe(TicketObserver::class);
        User::observe(UserObserver::class);
        VendorHasContact::observe(VendorHasContactObserver::class);
        Vendor::observe(VendorObserver::class);
        Secret::observe(SecretObserver::class);
        DeviceHasSecret::observe(DeviceHasSecretObserver::class);
    }
}
