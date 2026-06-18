<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freon_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->decimal('price_per_unit', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freon_inventory');
    }
};
