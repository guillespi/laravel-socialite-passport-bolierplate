<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one user for each role
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@pp.com',
        ]);
        $sellerUser = User::factory()->create([
            'name' => 'Seller User',
            'email' => 'seller@pp.com',
        ]);
        $marketingUser = User::factory()->create([
            'name' => 'Marketing User',
            'email' => 'marketing@pp.com',
        ]);
        $accountantUser = User::factory()->create([
            'name' => 'Accountant User',
            'email' => 'accountant@pp.com',
        ]);
        $customerServiceUser = User::factory()->create([
            'name' => 'Customer Service User',
            'email' => 'support@pp.com',
        ]);
        // Assign roles to users
        $adminUser->assignRole('admin');
        $sellerUser->assignRole('seller');
        $marketingUser->assignRole('marketing');
        $accountantUser->assignRole('accountant');
        $customerServiceUser->assignRole('customer-service');
    }
}
