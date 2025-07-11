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
        Schema::create('tb_laporan_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('laporan_keuangan')->nullable();
            $table->enum('status_laporan',['tervalidasi', 'belum tervalidasi']);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan_keuangan');
    }
};
