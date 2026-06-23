<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'user_id', 
        'has_diabetes', 'has_hypertension', 'has_preeclampsia', 
        'has_anemia', 'has_infections_urinary', 'has_thyroid_disorders', 
        'has_heart_disease', 'has_edema', 'has_bleeding', 
        'has_fetal_movement_issues', 'has_nausea_vomiting_severe', 
        'has_contractions_preterm', 
        'weight', 'blood_pressure', 'temperature', 'heart_rate', 'gestational_age',
        'next_appointment_date',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}