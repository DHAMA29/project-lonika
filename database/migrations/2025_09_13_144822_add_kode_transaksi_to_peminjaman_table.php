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
        // Only add column if it doesn't exist
        if (!Schema::hasColumn('peminjaman', 'kode_transaksi')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->string('kode_transaksi', 10)->nullable()->after('id');
            });
        }

        // Update existing records with transaction codes
        $peminjamans = DB::table('peminjaman')->orderBy('id')->get();
        foreach ($peminjamans as $index => $peminjaman) {
            if (empty($peminjaman->kode_transaksi)) {
                $kode = sprintf('0000%04d', $peminjaman->id);
                DB::table('peminjaman')
                    ->where('id', $peminjaman->id)
                    ->update(['kode_transaksi' => $kode]);
            }
        }

        // Make it unique after updating all records
        if (!Schema::hasColumn('peminjaman', 'kode_transaksi') || 
            !collect(Schema::getConnection()->getSchemaBuilder()->getIndexes('peminjaman'))
                ->contains(fn($index) => $index['name'] === 'peminjaman_kode_transaksi_unique')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->unique('kode_transaksi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropUnique(['kode_transaksi']);
            $table->dropColumn('kode_transaksi');
        });
    }
};
