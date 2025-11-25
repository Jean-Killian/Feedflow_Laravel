<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $table    = 'survey_answers';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'survey_id', 'survey_question_id', 'user_id',
        'answer',
        'created_at', 'updated_at'
    ];
    protected $casts = [
    ];
}
