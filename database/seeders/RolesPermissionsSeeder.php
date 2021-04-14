<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$role = Role::create(['name' => 'Admin', 'guard_name' => 'panel']);

		$permissions = ['access-role', 'create-role', 'edit-role', 'delete-role', 'access-user', 'create-user', 'edit-user', 'delete-user'];
		$permissionList = [];
		foreach ($permissions as $permission) {
			$permissionList[] = Permission::findOrCreate($permission, 'panel');
		}
		$role->syncPermissions($permissionList);
    }
}
