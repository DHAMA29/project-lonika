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
            $table->string('kode_diskon', 6)->nullable()->after('kode_transaksi');
            $table->decimal('nominal_diskon', 15, 2)->default(0)->after('kode_diskon');
            
            $table->foreign('kode_diskon')->references('kode_diskon')->on('diskon')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['kode_diskon']);
            $table->dropColumn(['kode_diskon', 'nominal_diskon']);
        });
    }
};
