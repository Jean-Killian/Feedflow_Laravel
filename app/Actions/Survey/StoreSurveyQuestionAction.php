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
            'question' => $dto->question,
            'type' => $dto->type,
            'data' => $dto->data,
        ]);
    }
}
