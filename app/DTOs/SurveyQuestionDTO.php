<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    //constructeur de la question
    public function __construct(
        public int $survey_id,
        public string $title,
        public string $question_type,
        public ?array $options = null,
    ) {}

    //convertis la requete text en dto
    public static function fromRequest(Request $request, int $survey_id): self
    {
        
        $options = $request->input('options');

        
        if (!empty($options)) {
            $options = array_map('trim', explode(',', $options));
        } else {
            $options = null;
        }

        return new self(
            survey_id: $survey_id,
            title: $request->input('title'),
            question_type: $request->input('question_type'),
            options: $options
        );
    }
}
