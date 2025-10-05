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
            // Add fields for better stock management
            $table->boolean('stock_reserved')->default(false)->after('status');
            $table->boolean('stock_deducted')->default(false)->after('stock_reserved');
            $table->boolean('stock_returned')->default(false)->after('stock_deducted');
            $table->timestamp('stock_deduction_date')->nullable()->after('stock_returned');
            $table->timestamp('stock_return_date')->nullable()->after('stock_deduction_date');
            
            // Add kode_transaksi if not exists
            if (!Schema::hasColumn('peminjaman', 'kode_transaksi')) {
                $table->string('kode_transaksi')->unique()->after('peminjam_id');
            }
            
            // Add kode_diskon and nominal_diskon if not exists
            if (!Schema::hasColumn('peminjaman', 'kode_diskon')) {
                $table->string('kode_diskon', 6)->nullable()->after('kode_transaksi');
            }
            if (!Schema::hasColumn('peminjaman', 'nominal_diskon')) {
                $table->decimal('nominal_diskon', 12, 2)->default(0)->after('kode_diskon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn([
                'stock_reserved',
                'stock_deducted', 
                'stock_returned',
                'stock_deduction_date',
                'stock_return_date'
            ]);
        });
    }
};
