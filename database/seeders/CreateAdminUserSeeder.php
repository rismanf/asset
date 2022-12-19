<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'name' => 'Risman Firmansyah', 
            'email' => 'risman.firmansyah@neutradc.id',
            'password' => bcrypt('password'),
            'created_by_id' => 1,
            'active' => true,
        ]);

        $user2 = User::create([
            'name' => 'Firmansyah', 
            'email' => 'risman.firmansyah45@gmail.com',
            'password' => bcrypt('password'),
            'created_by_id' => 1,
            'active' => true,
        ]);
      
        $role_superadmin = Role::create(['name' => 'superadmin']);
        $role_admin = Role::create(['name' => 'admin']);
       
        $permissions = Permission::pluck('id','id')->all();
     
        $role_superadmin->syncPermissions($permissions);
        $role_admin->syncPermissions($permissions);
       
        $user1->assignRole([$role_superadmin->id]);
        $user2->assignRole([$role_admin->id]);
    }
}
