<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    public function survey()
    {
        return $this->BelongsTo(Survey::class);
    }
    use HasFactory;

    protected $table    = 'survey_questions';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'survey_id',
        'title', 'question_type', 'options',
        'created_at', 'updated_at'
    ];
    protected $casts = [
    ];
}
