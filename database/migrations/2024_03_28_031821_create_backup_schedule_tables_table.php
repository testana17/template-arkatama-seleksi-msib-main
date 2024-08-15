<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_schedule_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('table_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_schedule_tables');
    }
};
