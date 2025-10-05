<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Peminjam;
use Illuminate\Support\Facades\DB;

echo "Checking for duplicate names and phones...\n";

// Check for duplicate names
$duplicateNames = DB::table('peminjam')
    ->select('nama', DB::raw('COUNT(*) as count'))
    ->groupBy('nama')
    ->having('count', '>', 1)
    ->get();

echo "Duplicate names found: " . $duplicateNames->count() . "\n";

foreach ($duplicateNames as $duplicate) {
    echo "- {$duplicate->nama} ({$duplicate->count} times)\n";
    
    // Get all records with this name
    $records = Peminjam::where('nama', $duplicate->nama)->get();
    
    // Keep the first one, delete the rest
    $first = $records->first();
    echo "  Keeping: ID {$first->id}\n";
    
    foreach ($records->skip(1) as $record) {
        echo "  Deleting: ID {$record->id}\n";
        $record->delete();
    }
}

// Check for duplicate phones
$duplicatePhones = DB::table('peminjam')
    ->select('telepon', DB::raw('COUNT(*) as count'))
    ->groupBy('telepon')
    ->having('count', '>', 1)
    ->get();

echo "\nDuplicate phones found: " . $duplicatePhones->count() . "\n";

foreach ($duplicatePhones as $duplicate) {
    echo "- {$duplicate->telepon} ({$duplicate->count} times)\n";
    
    // Get all records with this phone
    $records = Peminjam::where('telepon', $duplicate->telepon)->get();
    
    // Keep the first one, delete the rest
    $first = $records->first();
    echo "  Keeping: ID {$first->id}\n";
    
    foreach ($records->skip(1) as $record) {
        echo "  Deleting: ID {$record->id}\n";
        $record->delete();
    }
}

echo "\nDuplicate cleanup completed!\n";
