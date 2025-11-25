<?php

namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey; // <-- AJOUTER CETTE LIGNE

final class StoreSurveyAction
{
    public function execute(SurveyDTO $dto): Survey
    {
        return Survey::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'start_date' => $dto->start_date,
            'end_date' => $dto->end_date,
            'is_anonymous' => $dto->is_anonymous,
            'organization_id' => $dto->organization_id,
            'user_id' => $dto->user_id,
        ]);
    }
}
