<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear permisos
        $permissions = [
            'user.index',
            'user.edit',
            'category.index',
            'category.create',
            'category.edit',
            'category.delete',
            'product.index',
            'product.create',
            'product.edit',
            'product.delete',
            'order.index',
            'order.index.own',
            'order.edit',
            'payment.index',
            'payment.index.own',
            'statistic.index',
            'role.index',
            'role.create',
            'role.edit',
            'role.delete',
            'page.index',
            'sidebar.index',
            'sidebar.create',
            'sidebar.edit',
            'sidebar.delete',
            'searcher',
            'inventory.index',
            'inventory.create',
            'debtor.index'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $rolesAndPermissions = [
            'cliente' => [
                'order.index.own',
                'payment.index.own',
            ],
            'gerente comercial' => [
                'user.index',
                'user.edit',
                'category.index',
                'category.create',
                'category.edit',
                'category.delete',
                'product.index',
                'product.create',
                'product.edit',
                'product.delete',
                'order.index',
                'order.edit',
                'order.index.own',
                'payment.index',
                'payment.index.own',
                'statistic.index',
                'role.index',
                'role.create',
                'role.edit',
                'role.delete',
                'page.index',
                'sidebar.index',
                'sidebar.create',
                'sidebar.edit',
                'sidebar.delete',
                'searcher',
                'inventory.index',
                'inventory.create',
                'debtor.index'
            ],
            'proveedor' => [
                'product.index',
                'order.index',
                'order.edit',
                'inventory.index',
                'inventory.create'
            ],
        ];

        foreach ($rolesAndPermissions as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($permissions);
        }
    }
}
