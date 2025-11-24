<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    public function __construct(
        public int $survey_id,
        public string $question,
        public string $type,
        public ?array $data = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->survey_id,
            $request->question,
            $request->type,
            $request->input('data') // tableau pour JSON
        );
    }
}
