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
        Schema::table('peminjam', function (Blueprint $table) {
            // Add unique constraint for nama
            $table->unique('nama', 'peminjam_nama_unique');
            // Add unique constraint for telepon
            $table->unique('telepon', 'peminjam_telepon_unique');
            // Add composite unique constraint for nama + telepon combination
            $table->unique(['nama', 'telepon'], 'peminjam_nama_telepon_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjam', function (Blueprint $table) {
            // Drop unique constraints
            $table->dropUnique('peminjam_nama_unique');
            $table->dropUnique('peminjam_telepon_unique');
            $table->dropUnique('peminjam_nama_telepon_unique');
        });
    }
};
