<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_id')->constrained('jenis_barang')->cascadeOnDelete();
            $table->string('nama');
            $table->integer('stok');
            $table->decimal('harga_hari', 12, 2);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
