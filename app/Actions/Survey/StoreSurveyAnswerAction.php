<?php

namespace App\Actions\Survey;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\DTOs\SurveyAnswerDTO;
use App\Events\SurveyAnswerSubmitted;

final class StoreSurveyAnswerAction
{
    /**
     * Store a survey answer.
     */
    public function execute(SurveyAnswerDTO $dto): SurveyAnswer
    {
        $answer = SurveyAnswer::create([
            'user_id' => $dto->user_id,
            'survey_id' => $dto->survey_id,
            'survey_question_id' => $dto->survey_question_id,
            'answer' => $dto->getFormattedAnswer(),
        ]);

        $answer->load('question');
        $survey = Survey::find($dto->survey_id);

        SurveyAnswerSubmitted::dispatch($survey, $answer);

        return $answer;
    }
}
