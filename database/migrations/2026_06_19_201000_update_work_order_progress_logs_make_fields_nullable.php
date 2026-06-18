<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_order_progress_logs', function (Blueprint $table) {
            $table->foreignId('technician_id')->nullable()->change();
            $table->string('status')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('work_order_progress_logs', function (Blueprint $table) {
            $table->foreignId('technician_id')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
        });
    }
};
