<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->float('weight')->nullable(); // Poids en kg
            $table->string('blood_pressure')->nullable(); // ex: "120/80"
            $table->float('temperature')->nullable();
            $table->integer('heart_rate')->nullable(); // Pouls
            $table->integer('gestational_age')->nullable(); // Âge gestationnel (semaines)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            //
        });
    }
};
