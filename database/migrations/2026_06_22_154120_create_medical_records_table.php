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
    Schema::create('medical_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // --- Pathologies et Antécédents médicaux ---
        $table->boolean('has_diabetes')->default(false); // Diabète pré-existant ou gestationnel
        $table->boolean('has_hypertension')->default(false); // Hypertension chronique
        $table->boolean('has_preeclampsia')->default(false); // Pré-éclampsie / Toxémie
        $table->boolean('has_anemia')->default(false); // Anémie (carence en fer/folates)
        $table->boolean('has_infections_urinary')->default(false); // Infections urinaires à répétition
        $table->boolean('has_thyroid_disorders')->default(false); // Troubles thyroïdiens
        $table->boolean('has_heart_disease')->default(false); // Cardiopathie

        // --- Symptômes et complications de la grossesse ---
        $table->boolean('has_edema')->default(false); // Œdèmes (gonflements membres inf.)
        $table->boolean('has_bleeding')->default(false); // Hémorragies / Métrorragies
        $table->boolean('has_fetal_movement_issues')->default(false); // Diminution mouvements fœtaux
        $table->boolean('has_nausea_vomiting_severe')->default(false); // Hyperémèse gravidique
        $table->boolean('has_contractions_preterm')->default(false); // Contractions prématurées

        $table->text('notes')->nullable(); // Pour les observations du médecin
        $table->timestamps();
        $table->date('next_appointment_date')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
