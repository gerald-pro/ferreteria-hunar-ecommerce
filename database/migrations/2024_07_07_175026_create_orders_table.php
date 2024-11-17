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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('total_amount');
            $table->text('delivery_address')->nullable();
            $table->enum('payment_method', ['ELECTRONICO', 'CONTRA_ENTREGA'])->default('ELECTRONICO');
            $table->string('billing_name');
            $table->string('billing_id');
            $table->enum('delivery_status', ['CANCELADO', 'PENDIENTE', 'COMPLETADO'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
