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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_penumpangs')->references('id')->on('penumpangs');
            $table->foreignUuid('id_travel')->references('id')->on('travel');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->timestamp('tanggal_pemesanan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
