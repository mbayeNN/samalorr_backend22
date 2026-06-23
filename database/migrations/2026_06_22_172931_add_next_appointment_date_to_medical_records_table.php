<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Ajout de la colonne pour le rendez-vous
            $table->date('next_appointment_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Permet de supprimer la colonne si vous annulez la migration
            $table->dropColumn('next_appointment_date');
        });
    }
};