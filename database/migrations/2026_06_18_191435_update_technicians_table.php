<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("technicians", function (Blueprint $table) {
            $table->renameColumn("nik", "identity");
        });

        Schema::table("technicians", function (Blueprint $table) {
            $table->date("start_date")->nullable()->after("identity");
        });
    }

    public function down(): void
    {
        Schema::table("technicians", function (Blueprint $table) {
            $table->renameColumn("identity", "nik");
            $table->dropColumn("start_date");
        });
    }
};
