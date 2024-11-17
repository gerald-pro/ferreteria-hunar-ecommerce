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
        Schema::create('sidebar_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('page_id')->constrained();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('visits')->default(0);
            $table->foreignId('sidebar_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('permission')->nullable();
            $table->unsignedSmallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_items');
    }
};
