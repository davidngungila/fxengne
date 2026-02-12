<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@fxengne.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Trader User
        User::updateOrCreate(
            ['email' => 'trader@fxengne.com'],
            [
                'name' => 'Trader User',
                'password' => Hash::make('trader123'),
                'role' => 'trader',
                'email_verified_at' => now(),
            ]
        );

        // Create Additional Sample Traders
        User::updateOrCreate(
            ['email' => 'john@fxengne.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
                'role' => 'trader',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane@fxengne.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password123'),
                'role' => 'trader',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Sample users created successfully!');
        $this->command->info('');
        $this->command->info('Admin Login:');
        $this->command->info('  Email: admin@fxengne.com');
        $this->command->info('  Password: admin123');
        $this->command->info('');
        $this->command->info('Trader Login:');
        $this->command->info('  Email: trader@fxengne.com');
        $this->command->info('  Password: trader123');
        $this->command->info('');
        $this->command->info('Additional Traders:');
        $this->command->info('  Email: john@fxengne.com / Password: password123');
        $this->command->info('  Email: jane@fxengne.com / Password: password123');
    }
}
