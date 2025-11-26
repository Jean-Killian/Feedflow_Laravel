<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//représente une question d’un sondage dans la base de données
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

    //récupère le sondage auquel appartient la question.
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    //liste toutes les réponses envoyées par les utilisateurs.
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

}
