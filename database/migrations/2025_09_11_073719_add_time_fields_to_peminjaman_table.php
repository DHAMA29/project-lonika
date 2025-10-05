<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->time('jam_pinjam')->nullable()->after('tanggal_pinjam');
            $table->time('jam_kembali')->nullable()->after('tanggal_kembali');
            $table->integer('total_jam')->nullable()->after('lama_hari')->comment('Total jam peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['jam_pinjam', 'jam_kembali', 'total_jam']);
        });
    }
};
