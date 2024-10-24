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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del plan, por ejemplo, 'Básico', 'Pro', etc.
            $table->text('description')->nullable(); // Descripción del plan
            $table->decimal('price', 8, 2); // Precio del plan
            $table->integer('duration'); // Duración del plan en días (por ejemplo, 30, 365, etc.)
            $table->string('currency', 3)->default('USD'); // Moneda, por defecto USD
            $table->boolean('is_active')->default(true); // Si el plan está activo o no
            $table->text('features')->nullable(); // Puedes almacenar características como JSON o en texto plano
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
