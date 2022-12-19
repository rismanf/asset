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
            'customer-list',
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
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
