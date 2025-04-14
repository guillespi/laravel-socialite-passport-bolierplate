<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $marketingRole = Role::firstOrCreate(['name' => 'marketing']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $customerServiceRole = Role::firstOrCreate(['name' => 'customer-service']);

        // Define the permissions for each route
        $permissions = [
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        // Create each permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all()); // Admin has all permissions

        $sellerRole->givePermissionTo([
            'view users',
        ]);

        $marketingRole->givePermissionTo([
        ]);

        $accountantRole->givePermissionTo([
        ]);

        $customerServiceRole->givePermissionTo([
        ]);

        // Optionally assign roles to specific users
        $users = [
            'admin@example.com' => 'admin',
            'seller@example.com' => 'seller',
            'marketing@example.com' => 'marketing',
            'accountant@example.com' => 'accountant',
            'support@example.com' => 'customer-service',
        ];

        foreach ($users as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($role);
            }
        }
    }
}
