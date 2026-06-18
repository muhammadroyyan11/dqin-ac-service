<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('type');
            $table->decimal('pk', 4, 1);
            $table->string('serial_number')->nullable();
            $table->string('installation_location')->nullable();
            $table->enum('warranty_status', ['active', 'expired', 'none'])->default('none');
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_units');
    }
};
