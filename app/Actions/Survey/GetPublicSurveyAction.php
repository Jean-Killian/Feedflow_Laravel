<?php

namespace App\Actions\Survey;

use App\Models\Survey;
use Illuminate\Support\Facades\Crypt;

class GetPublicSurveyAction
{
    public function execute(string $token): Survey
    {
        // Décrypte le token en surveillant les erreurs
        try {
            $surveyId = Crypt::decryptString($token);
        } catch (\Exception $e) {
            abort(404, "Lien invalide.");
        }

        // Récupère le sondage
        $survey = Survey::findOrFail($surveyId);

        // Vérifie la période active
        $now = now();
        if (!($now->between($survey->start_date, $survey->end_date))) {
            abort(403, "Ce sondage n'est pas actif.");
        }

        return $survey;
    }
}
