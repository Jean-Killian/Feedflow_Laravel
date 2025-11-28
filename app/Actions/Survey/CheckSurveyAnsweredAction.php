<?php

namespace App\Actions\Survey;

use App\Models\Survey;
use App\Models\User;

class CheckSurveyAnsweredAction
{
    public function execute(Survey $survey, ?User $user): bool
    {
        return $survey->answers->contains(function($answer) use ($user) {

            // Si la réponse est anonyme, on considère "répondu"
            if ($answer->user_id === null) {
                return true;
            }

            // Si utilisateur connecté, on compare l'id
            return $user && $answer->user_id === $user->id;
        });
    }
}
