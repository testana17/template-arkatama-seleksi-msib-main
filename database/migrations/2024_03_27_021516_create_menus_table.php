<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->id();
            $table->string('name', 200);
            $table->string('module', 200)->nullable()->unique();
            $table->string('slug', 200)->unique();
            $table->enum('type', ['group', 'menu', 'divider'])->default('menu');
            $table->string('icon', 200)->nullable();
            $table->string('url', 200)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->enum('is_active', ['0', '1'])->default('1')->comment('0=Non-Active, 1=Active');
            $table->enum('location', ['sidebar', 'topbar'])->default('sidebar');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
