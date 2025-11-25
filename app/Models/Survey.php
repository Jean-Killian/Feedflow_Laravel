<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    public function surveyQuestion()
    {
        return $this->hasMany(SurveyQuestion::class);
    }
    
    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

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
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
