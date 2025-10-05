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
        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('kode_diskon', 6)->unique();
            $table->decimal('persentase', 5, 2); // max 999.99%
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('jenis_kode', ['acak', 'manual'])->default('manual');
            $table->integer('batas_penggunaan')->nullable(); // null = unlimited
            $table->integer('jumlah_terpakai')->default(0);
            $table->datetime('tanggal_mulai')->nullable();
            $table->datetime('tanggal_berakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskon');
    }
};
