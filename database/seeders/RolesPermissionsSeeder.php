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

		$permissions = [
		    'access-role', 'create-role', 'edit-role', 'delete-role',
            'access-admin', 'create-admin', 'edit-admin', 'delete-admin',
            'access-user', 'create-user', 'edit-user', 'delete-user',
            'access-category', 'create-category', 'edit-category', 'delete-category',
            'access-media', 'create-media', 'edit-media', 'delete-media',
            'access-post', 'create-post', 'edit-post', 'delete-post',
            'access-log', 'create-log', 'edit-log', 'delete-log',
            'access-attribute', 'create-attribute', 'edit-attribute', 'delete-attribute',
        ];
		$permissionList = [];
		foreach ($permissions as $permission) {
			$permissionList[] = Permission::findOrCreate($permission, 'panel');
		}
		$role->syncPermissions($permissionList);
    }
}
