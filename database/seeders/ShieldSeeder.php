<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"\u8d85\u7ea7\u7ba1\u7406\u5458","guard_name":"web","permissions":["view_asset::number::rule","view_any_asset::number::rule","create_asset::number::rule","update_asset::number::rule","delete_asset::number::rule","delete_any_asset::number::rule","create_brand","update_brand","delete_brand","delete_any_brand","import_brand","export_brand","view_device","view_any_device","create_device","update_device","delete_device","delete_any_device","assign_user_device","delete_assign_user_device","import_device","export_device","retire_device","force_retire_device","set_auto_asset_number_rule_device","reset_auto_asset_number_rule_device","set_retire_flow_device","create_has_part_device","delete_has_part_device","create_has_software_device","delete_has_software_device","create_device::category","update_device::category","delete_device::category","delete_any_device::category","import_device::category","export_device::category","view_flow","view_any_flow","create_flow","update_flow","delete_flow","delete_any_flow","view_flow::has::form","view_any_flow::has::form","create_flow::has::form","update_flow::has::form","delete_flow::has::form","delete_any_flow::has::form","view_import","view_any_import","create_import","update_import","restore_import","restore_any_import","replicate_import","reorder_import","delete_import","delete_any_import","force_delete_import","force_delete_any_import","view_inventory","view_any_inventory","create_inventory","update_inventory","delete_inventory","delete_any_inventory","view_organization","view_any_organization","create_organization","update_organization","delete_organization","delete_any_organization","view_part","view_any_part","create_part","update_part","delete_part","delete_any_part","import_part","export_part","retire_part","force_retire_part","set_auto_asset_number_rule_part","reset_auto_asset_number_rule_part","set_retire_flow_part","create_has_part_part","delete_has_part_part","create_part::category","update_part::category","delete_part::category","delete_any_part::category","import_part::category","export_part::category","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_software","view_any_software","create_software","update_software","delete_software","delete_any_software","import_software","export_software","retire_software","force_retire_software","set_auto_asset_number_rule_software","reset_auto_asset_number_rule_software","set_retire_flow_software","create_has_software_software","delete_has_software_software","create_software::category","update_software::category","delete_software::category","delete_any_software::category","import_software::category","export_software::category","view_user","view_any_user","create_user","update_user","delete_user","delete_any_user","import_user","export_user","reset_password_user","view_vendor","view_any_vendor","create_vendor","update_vendor","delete_vendor","delete_any_vendor","import_vendor","export_vendor","page_Themes","check_inventory","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_ticket::category","view_any_ticket::category","create_ticket::category","update_ticket::category","restore_ticket::category","restore_any_ticket::category","replicate_ticket::category","reorder_ticket::category","delete_ticket::category","delete_any_ticket::category","force_delete_ticket::category","force_delete_any_ticket::category"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use ($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web',
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {

            foreach ($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
