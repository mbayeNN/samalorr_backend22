<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // 1. Ajouter la nouvelle colonne
        Schema::table('users', function (Blueprint $table) {
            $table->date('start_pregnancy_date')->nullable();
        });

        // 2. Transférer les données : On calcule la date approximative 
        // basée sur le nombre de semaines actuelles stockées
        DB::statement("UPDATE users SET start_pregnancy_date = DATE_SUB(CURDATE(), INTERVAL weeks_of_pregnancy WEEK) WHERE weeks_of_pregnancy IS NOT NULL");

        // 3. Supprimer l'ancienne colonne
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weeks_of_pregnancy');
        });
    }

    public function down()
    {
        // En cas d'annulation (si besoin), on recrée l'ancienne colonne
        Schema::table('users', function (Blueprint $table) {
            $table->integer('weeks_of_pregnancy')->nullable();
            $table->dropColumn('start_pregnancy_date');
        });
    }
};