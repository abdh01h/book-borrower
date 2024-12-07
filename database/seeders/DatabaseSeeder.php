<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $admin_user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        $users = User::factory(30)->create();

        // Create permissions
        $user_permissions = [
            'my book.read', 'book.read',
        ];

        $all_permissions = [
            'book.create', 'book.update', 'book.delete',
            'user.read', 'user.create', 'user.update', 'user.delete',
            'role.read', 'role.create', 'role.update', 'role.delete',
        ];

        $all_permissions = array_merge((array) $user_permissions, (array) $all_permissions);

        foreach($all_permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to admin role
        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo(Permission::all());
        $admin_user->assignRole('admin');

        // Assign permissions to user role
        $user_role = Role::create(['name' => 'user']);
        $user_role->givePermissionTo($user_permissions);
        foreach($users as $user) {
            $user->assignRole('user');
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    }
}
