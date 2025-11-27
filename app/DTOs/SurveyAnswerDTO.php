<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use App\Models\Survey;

final class SurveyAnswerDTO
{
    public function __construct(
        public int $survey_id,
        public int $survey_question_id,
        public ?int $user_id,
        public string|array $answer,
    ) {}

    /**
     * Create DTO from request for a specific question.
     */
    public static function fromRequest(Request $request, Survey $survey, int $questionId, ?int $userId): self
    {
        $answers = $request->input('answers', []);
        $answerValue = $answers[$questionId] ?? '';

        return new self(
            survey_id: $survey->id,
            survey_question_id: $questionId,
            user_id: $userId,
            answer: $answerValue,
        );
    }

    /**
     * Get the answer value formatted for storage.
     */
    public function getFormattedAnswer(): string
    {
        return is_array($this->answer) ? json_encode($this->answer) : $this->answer;
    }
}
