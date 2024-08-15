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
        Schema::create('slideshow_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('slideshow_id')->constrained('slideshow')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title');
            $table->text('caption');
            $table->string('image');
            $table->integer('order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slideshow_items');
    }
};
