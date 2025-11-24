<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $table = 'survey_questions';
    protected $fillable = [
        'survey_id',
        'question',
        'type',
        'data', // options JSON pour les questions à choix multiples
    ];

    protected $casts = [
        'data' => 'array', // convertit automatiquement JSON <-> array
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
