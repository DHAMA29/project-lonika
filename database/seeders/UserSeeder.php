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
        // Create admin users
        User::updateOrCreate(
            ['email' => 'admin@lonika.com'],
            [
                'name' => 'Admin Utama Lonika',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'last_login_at' => now()->subMinutes(15),
            ]
        );

        User::updateOrCreate(
            ['email' => 'supervisor@lonika.com'],
            [
                'name' => 'Supervisor Lonika',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@lonika.com'],
            [
                'name' => 'Manager Sistem',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
            ]
        );

        // Create regular users
        User::updateOrCreate(
            ['email' => 'operator@lonika.com'],
            [
                'name' => 'Operator Lonika',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'last_login_at' => null, // Never logged in
            ]
        );

        User::updateOrCreate(
            ['email' => 'teknisi@lonika.com'],
            [
                'name' => 'Teknisi Peralatan',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => null, // Not verified
                'last_login_at' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@lonika.com'],
            [
                'name' => 'Staff Operasional',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(5),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@lonika.com'],
            [
                'name' => 'Kasir Lonika',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(2),
            ]
        );
    }
}
