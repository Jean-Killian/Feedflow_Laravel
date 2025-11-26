<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\DTOs\SurveyDTO;
use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\DTOs\SurveyQuestionDTO;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Log;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;


class SurveyController extends Controller
{
    public function index()
    {

        $organizationId = 1;
        $surveys = Survey::with('questions')->where('organization_id', $organizationId)->get();


        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('surveys.create');
    }

    /**
     * 
     */
    public function store(StoreSurveyRequest $request)
    {
        $this->authorize('create', Survey::class);

        // Crée le DTO avec organisation 1
        $dto = SurveyDTO::fromRequest($request);
        $survey = app(StoreSurveyAction::class)->execute($dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage créé !');
    }


    public function show(Survey $survey)
    {
        $this->authorize('view', $survey);

        return view('surveys.show', compact('survey'));
    }


    public function edit(Survey $survey)
    {
        $this->authorize('update', $survey); 

        return view('surveys.edit', compact('survey'));
    }

    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $this->authorize('update', $survey); 

        $dto = SurveyDTO::fromRequest($request);
        app(UpdateSurveyAction::class)->execute($dto, $survey);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour !');
    }


    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);

        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé !');
    }

    public function addQuestion(Request $request, Survey $survey)
    {
        $this->authorize('update', $survey);

        $dto = SurveyQuestionDTO::fromRequest($request, $survey->id);
        app(StoreSurveyQuestionAction::class)->execute($dto);

        return redirect()->route('surveys.index', $survey)
            ->with('success', 'Question ajoutée !');
    }


    public function addQuestionForm(Survey $survey)
    {
        $this->authorize('update', $survey);

        return view('surveys.add_question', compact('survey'));
    }

    public function editQuestions(Survey $survey)
    {
        $this->authorize('update', $survey);

        $questions = $survey->questions; // récupère toutes les questions
        return view('surveys.questions.edit', compact('survey', 'questions'));
    }

    public function takeSurvey(Survey $survey)
    {
        $user = auth()->user();
        $survey->load('questions'); 
        return view('surveys.take', compact('survey'));

        // Vérifie si l'utilisateur a déjà répondu à ce sondage
        $hasAnswered = $survey->answers()->where('user_id', $user->id)->exists();

        if ($hasAnswered) {
            return redirect()->route('surveys.index')
                ->with('info', 'Vous avez déjà répondu à ce sondage.');
        }

        return view('surveys.take', compact('survey'));
    }

    public function updateQuestions(Request $request, Survey $survey)
    {
        $questionsData = $request->input('questions', []);

        foreach ($questionsData as $id => $data) {
            $question = $survey->questions()->find($id);
            if (!$question) continue;

            // Transforme les options en array si ce n'est pas déjà fait
            $options = $data['options'] ?? [];
            if (is_string($options)) {
                // par ex. "option1, option2, option3"
                $options = array_map('trim', explode(',', $options));
            }

            $question->update([
                'title' => $data['title'],
                'question_type' => $data['question_type'],
                'options' => $options, // Laravel castera en JSON
            ]);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Questions mises à jour !');
    }


public function submitSurvey(Request $request, Survey $survey)
{
    // Récupère l'utilisateur actuel
    $user = $request->user();
    
    // Pour sondage anonyme, pas de user_id
    $userId = $survey->is_anonymous ? null : $user->id;

    // Vérifie si l'utilisateur a déjà répondu (uniquement si non anonyme)
    if (!$survey->is_anonymous && SurveyAnswer::where('survey_id', $survey->id)
        ->where('user_id', $user->id)
        ->exists()
    ) {
        return redirect()->route('surveys.index')
            ->with('info', 'Vous avez déjà répondu à ce sondage.');
    }

    $answers = $request->input('answers', []);

    foreach ($survey->questions as $question) {

        // Récupère la réponse envoyée pour cette question
        $answerValue = $answers[$question->id] ?? null;

        // Ignore si aucune réponse
        if ($answerValue === null) {
            continue;
        }

        // Si la réponse est un tableau (checkbox, QCM multiple), convertit en JSON
        if (is_array($answerValue)) {
            $answerValue = json_encode($answerValue);
        }

        // Insère dans la table survey_answers
        DB::table('survey_answers')->insert([
            'user_id' => $userId,
            'survey_question_id' => $question->id,
            'survey_id' => $survey->id,
            'answer' => $answerValue,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('surveys.index')
        ->with('success', 'Merci ! Votre sondage a été enregistré.');
}


}

