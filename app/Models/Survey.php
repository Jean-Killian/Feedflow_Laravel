<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{

    public function surveyQuestion()
    {
        return $this->hasMany(SurveyQuestion::class);
    }
    
    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organization::class);
    }
    
    use HasFactory;

    protected $table    = 'surveys';
    public $timestamps  = true;
    protected $fillable = [
    'organization_id',
    'user_id',
    'title',
    'description',
    'start_date',
    'end_date',
    'is_anonymous',
    ];

    protected $casts = [
    'is_anonymous' => 'boolean',
    'start_date' => 'date',
    'end_date' => 'date',
];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

}
