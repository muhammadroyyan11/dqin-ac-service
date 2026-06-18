<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->constrained()->onDelete('cascade');
            $table->boolean('is_captain')->default(false);
            $table->enum('status', ['assigned', 'in_progress', 'completed'])->default('assigned');
            $table->text('progress_note')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['work_order_id', 'technician_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_technicians');
    }
};
