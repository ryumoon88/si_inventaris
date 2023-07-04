<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
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

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_MyProfile","view_item","view_any_item","create_item","update_item","restore_item","restore_any_item","replicate_item","reorder_item","delete_item","delete_any_item","force_delete_item","force_delete_any_item","view_item::category","view_any_item::category","create_item::category","update_item::category","restore_item::category","restore_any_item::category","replicate_item::category","reorder_item::category","delete_item::category","delete_any_item::category","force_delete_item::category","force_delete_any_item::category","view_supplier","view_any_supplier","create_supplier","update_supplier","restore_supplier","restore_any_supplier","replicate_supplier","reorder_supplier","delete_supplier","delete_any_supplier","force_delete_supplier","force_delete_any_supplier","view_loan","view_any_loan","create_loan","update_loan","restore_loan","restore_any_loan","replicate_loan","reorder_loan","delete_loan","delete_any_loan","force_delete_loan","force_delete_any_loan","reorder_shield::role","view_item::transaction","view_any_item::transaction","create_item::transaction","update_item::transaction","delete_item::transaction","delete_any_item::transaction","view_activity","view_any_activity","create_activity","update_activity","restore_activity","restore_any_activity","replicate_activity","reorder_activity","delete_activity","delete_any_activity","force_delete_activity","force_delete_any_activity","approve_item::transaction","force_approve_item::transaction","reject_item::transaction","force_reject_item::transaction","pending_item::transaction","force_pending_item::transaction"]},{"name":"user","guard_name":"web","permissions":["view_item","view_any_item","view_item::category","view_any_item::category","view_supplier","view_any_supplier","view_item::transaction","view_any_item::transaction"]},{"name":"Admin","guard_name":"web","permissions":["view_user","view_any_user","update_user","delete_user"]}]';
        $directPermissions = '{"92":{"name":"restore_item::transaction","guard_name":"web"},"93":{"name":"restore_any_item::transaction","guard_name":"web"},"94":{"name":"replicate_item::transaction","guard_name":"web"},"95":{"name":"reorder_item::transaction","guard_name":"web"},"96":{"name":"force_delete_item::transaction","guard_name":"web"},"97":{"name":"force_delete_any_item::transaction","guard_name":"web"},"98":{"name":"view_::models","guard_name":"web"},"99":{"name":"view_any_::models","guard_name":"web"},"100":{"name":"create_::models","guard_name":"web"},"101":{"name":"update_::models","guard_name":"web"},"102":{"name":"restore_::models","guard_name":"web"},"103":{"name":"restore_any_::models","guard_name":"web"},"104":{"name":"replicate_::models","guard_name":"web"},"105":{"name":"reorder_::models","guard_name":"web"},"106":{"name":"delete_::models","guard_name":"web"},"107":{"name":"delete_any_::models","guard_name":"web"},"108":{"name":"force_delete_::models","guard_name":"web"},"109":{"name":"force_delete_any_::models","guard_name":"web"},"110":{"name":"update_status_item::transaction","guard_name":"web"},"111":{"name":"view_shield::::role","guard_name":"web"},"112":{"name":"view_any_shield::::role","guard_name":"web"},"113":{"name":"create_shield::::role","guard_name":"web"},"114":{"name":"update_shield::::role","guard_name":"web"},"115":{"name":"delete_shield::::role","guard_name":"web"},"116":{"name":"delete_any_shield::::role","guard_name":"web"},"117":{"name":"reorder_shield::::role","guard_name":"web"},"118":{"name":"restore_shield::role","guard_name":"web"},"119":{"name":"restore_any_shield::role","guard_name":"web"},"120":{"name":"replicate_shield::role","guard_name":"web"},"121":{"name":"force_delete_shield::role","guard_name":"web"},"122":{"name":"force_delete_any_shield::role","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

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
