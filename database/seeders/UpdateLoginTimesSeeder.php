<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateLoginTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update login times untuk testing online status
        User::where('email', 'admin@lonika.com')->update(['last_login_at' => now()]);
        User::where('email', 'supervisor@lonika.com')->update(['last_login_at' => now()->subMinutes(2)]);
        User::where('email', 'manager@lonika.com')->update(['last_login_at' => now()->subMinutes(10)]);
        
        echo "Updated login times for testing online status:\n";
        echo "- admin@lonika.com: Online (just now)\n";
        echo "- supervisor@lonika.com: Online (2 minutes ago)\n";
        echo "- manager@lonika.com: Offline (10 minutes ago)\n";
    }
}
