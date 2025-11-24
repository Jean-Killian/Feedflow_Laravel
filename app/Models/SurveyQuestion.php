<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $table    = 'surveys';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'survey_id',
        'title', 'question_type', 'options',
        'created_at', 'updated_at'
    ];
    protected $casts = [
    ];
}
