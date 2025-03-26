<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Permission::count() == 0) {
            foreach ($this->getPermissions() as $permissionName) {
                Permission::create(['name' => $permissionName]);
            }
        }

        if (Role::count() == 0) {
            $role = Role::create(['name' => 'Super Admin']);
            $permissions = Permission::all();
            $role->syncPermissions($permissions);
        }
    }

    private function getPermissions(): array
    {
        return [
            'User Index',
            'User Store',
            'User Update',
            'User Edit',
            'User Delete',

            'Role Index',
            'Role Store',
            'Role Update',
            'Role Edit',
            'Role Delete',

            'Permission Index',
            'Permission Store',
            'Permission Update',
            'Permission Edit',
            'Permission Delete',

            'Category Index',
            'Category Store',
            'Category Update',
            'Category Edit',
            'Category Delete',
            'Category Search',
            'Category Export',
            'Category Bulk Delete',
            'Category Bulk Restore',

            'Sub-Category Index',
            'Sub-Category Store',
            'Sub-Category Update',
            'Sub-Category Edit',
            'Sub-Category Delete',
            'Sub-Category Search',
            'Sub-Category Export',
            'Sub-Category Bulk Delete',
            'Sub-Category Bulk Restore',

            'Brand Index',
            'Brand Store',
            'Brand Update',
            'Brand Edit',
            'Brand Delete',
            'Brand Search',
            'Brand Export',
            'Brand Bulk Delete',
            'Brand Bulk Restore',

            'Tag Index',
            'Tag Store',
            'Tag Update',
            'Tag Edit',
            'Tag Delete',
            'Tag Search',
            'Tag Export',
            'Tag Bulk Delete',
            'Tag Bulk Restore',

            'Product Index',
            'Product Store',
            'Product Update',
            'Product Edit',
            'Product Delete',
            'Product Search',
            'Product Export',
            'Product Bulk Delete',
            'Product Bulk Restore',

            'Payment Method Index',
            'Payment Method Store',
            'Payment Method Update',
            'Payment Method Edit',
            'Payment Method Delete',
            'Payment Method Search',
            'Payment Method Export',
            'Payment Method Bulk Delete',
            'Payment Method Bulk Restore',
        ];
    }
}
