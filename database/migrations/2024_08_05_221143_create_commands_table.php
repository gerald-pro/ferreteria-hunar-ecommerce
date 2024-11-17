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
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('action');
            $table->string('target')->nullable();
            $table->text('description')->nullable();
            $table->string('example')->nullable();
            $table->enum('access_level', ['PUBLICO', 'RESTRINGIDO'])->default('RESTRINGIDO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};
