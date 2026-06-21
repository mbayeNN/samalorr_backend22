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
    Schema::table('users', function (Blueprint $table) {
        // On vérifie avant d'ajouter
        if (!Schema::hasColumn('users', 'city')) {
            $table->string('city')->nullable();
        }
        
        if (!Schema::hasColumn('users', 'weeks_of_pregnancy')) {
            $table->integer('weeks_of_pregnancy')->default(0);
        }
        
        if (!Schema::hasColumn('users', 'is_profile_verified')) {
            $table->boolean('is_profile_verified')->default(false);
        }

        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone')->unique()->nullable();
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['city', 'is_profile_verified']);
    });
}
};
