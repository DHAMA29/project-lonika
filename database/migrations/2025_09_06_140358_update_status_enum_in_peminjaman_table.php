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
        // 1. Ubah enum agar menerima nilai baru
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('belum dikembalikan', 'selesai', 'pending', 'approved', 'ongoing', 'returned', 'cancelled') DEFAULT 'belum dikembalikan'");

        // 2. Update data yang tidak valid
        DB::table('peminjaman')
            ->whereNotIn('status', ['belum dikembalikan', 'selesai'])
            ->update(['status' => 'belum dikembalikan']);

        // 3. Restrict enum ke nilai baru saja
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('belum dikembalikan', 'selesai') DEFAULT 'belum dikembalikan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika ingin mengembalikan ke enum sebelumnya, tulis enum lama di sini
        // Contoh:
        // DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('belum dikembalikan', 'diproses', 'selesai') DEFAULT 'belum dikembalikan'");
    }
};
