<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',
            'contact-list',
            'contact-create',
            'contact-edit',
            'contact-delete',
            'site-list',
            'site-create',
            'site-edit',
            'site-delete',
            'floor-list',
            'floor-create',
            'floor-edit',
            'floor-delete',
            'room-list',
            'room-create',
            'room-edit',
            'room-delete',
            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',
            'bisnisunit-list',
            'bisnisunit-create',
            'bisnisunit-edit',
            'bisnisunit-delete',
            'vendor-list',
            'vendor-create',
            'vendor-edit',
            'vendor-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'asset-list',
            'asset-create',
            'asset-edit',
            'asset-delete',
            'customer-list',///asset
            'customer-create',
            'customer-edit',
            'customer-delete',
            'rack-list',
            'rack-create',
            'rack-edit',
            'rack-delete',
            'request-list',
            'request-create',
            'request-edit',
            'request-delete',
            'movein-list',
            'movein-create',
            'movein-edit',
            'movein-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
