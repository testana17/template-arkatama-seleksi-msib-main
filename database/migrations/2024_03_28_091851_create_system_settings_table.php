<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            $table->string('name', 200)->unique();
            $table->string('value', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'array', 'object', 'null'])->default('string');
            $table->enum('is_active', ['0', '1'])->default('1')->comment('0=Non-Active, 1=Active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
