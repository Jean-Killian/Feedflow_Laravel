<?php

namespace App\Actions\Survey;

use App\Models\SurveyQuestion;
use App\DTOs\SurveyQuestionDTO;

final class StoreSurveyQuestionAction
{
    public function execute(SurveyQuestionDTO $dto): SurveyQuestion
    {
        return SurveyQuestion::create([
            'survey_id' => $dto->survey_id,
            'title' => $dto->title,
            'question_type' => $dto->question_type,
            'options' => $dto->options,
        ]);
    }
}
