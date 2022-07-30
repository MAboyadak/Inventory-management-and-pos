<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'store user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'update user']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Super-Admin']);
        $role1->givePermissionTo(['view users','add user','store user','delete user','edit user','update user']);
        // $role1->givePermissionTo('delete articles');

        $role2 = Role::create(['name' => 'user']);
        $role2->givePermissionTo('view users');
        // $role2->givePermissionTo('unpublish articles');

        // $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::create([
            'name' => 'User1',
            'password' => Hash::make('123456789'),
            'phone' => '554466'
            // 'email' => 'test@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::create([
            'name' => 'admin',
            'password' => Hash::make('123456789'),
            'phone' => '01010568214'
        ]);
        $user->assignRole($role1);

        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Example Super-Admin User',
        //     'email' => 'superadmin@example.com',
        // ]);
        // $user->assignRole($role3);
    }
}
