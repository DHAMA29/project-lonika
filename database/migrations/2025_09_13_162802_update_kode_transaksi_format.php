<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing transaction codes to new format (YYYYMM####)
        $peminjamans = DB::table('peminjaman')->orderBy('created_at')->get();
        
        foreach ($peminjamans as $index => $peminjaman) {
            // Get the creation date or use current date
            $createdAt = $peminjaman->created_at ? 
                \Carbon\Carbon::parse($peminjaman->created_at) : 
                \Carbon\Carbon::now();
            
            // Generate new format: YYYYMM + sequence number
            $prefix = $createdAt->format('Ym');
            $sequence = $index + 1;
            $newKode = $prefix . sprintf('%04d', $sequence);
            
            // Check if this code already exists, if yes increment
            while (DB::table('peminjaman')->where('kode_transaksi', $newKode)->where('id', '!=', $peminjaman->id)->exists()) {
                $sequence++;
                $newKode = $prefix . sprintf('%04d', $sequence);
            }
            
            // Update the record
            DB::table('peminjaman')
                ->where('id', $peminjaman->id)
                ->update(['kode_transaksi' => $newKode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old format (0000####)
        $peminjamans = DB::table('peminjaman')->orderBy('id')->get();
        
        foreach ($peminjamans as $peminjaman) {
            $oldKode = sprintf('0000%04d', $peminjaman->id);
            
            DB::table('peminjaman')
                ->where('id', $peminjaman->id)
                ->update(['kode_transaksi' => $oldKode]);
        }
    }
};
