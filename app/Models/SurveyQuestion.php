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
        'title',
        'question_type',
        'options', 
    ];

    protected $casts = [
        'options' => 'array', 
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
