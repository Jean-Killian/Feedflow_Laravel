<?php

namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey; 


final class UpdateSurveyAction
{
    public function execute(SurveyDTO $dto, Survey $survey): Survey
    {
        $survey->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'start_date' => $dto->start_date,
            'end_date' => $dto->end_date,
            'is_anonymous' => $dto->is_anonymous,
            'organization_id' => $dto->organization_id,
        ]);

        return $survey;
    }
}