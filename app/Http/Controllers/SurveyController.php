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
use Illuminate\Support\Facades\Crypt;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
    $user = auth()->user();
    $organizationId = $request->get('organization_id');
    
    // Vérifier que l'organization_id est fourni
    if (!$organizationId) {
        abort(400, 'Aucune organisation sélectionnée.');
    }
    
    // Vérifier que l'organisation existe
    $organization = \App\Models\Organization::find($organizationId);
    if (!$organization) {
        abort(404, 'Organisation introuvable.');
    }
    
    // Filtrer les sondages par organisation
    $surveys = Survey::with('answers')
        ->where('organization_id', $organizationId)
        ->get();

    foreach ($surveys as $survey) {
        $survey->hasAnswered = $survey->answers->contains(function($answer) use ($user) {
            return $answer->user_id === $user->id || $answer->user_id === null;
        });
    }

    return view('surveys.index', compact('surveys', 'organization'));
    }


    public function create(Request $request)
    {
        $organizationId = $request->get('organization_id');
        
        $organization = \App\Models\Organization::find($organizationId);

        return view('surveys.create', compact('organization'));
    }

    
    public function store(StoreSurveyRequest $request)
    {
        $this->authorize('create', Survey::class);

        $dto = SurveyDTO::fromRequest($request);
        $survey = app(StoreSurveyAction::class)->execute($dto);

        return redirect()->route('surveys.index', ['organization_id' => $request->organization_id])
            ->with('success', 'Sondage créé !');
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
        $user = $request->user();
        $userId = $request->has('respond_anonymously') ? null : $user->id;

        // Vérifie si l'utilisateur connecté a déjà répondu (uniquement si pas anonyme)
        if ($userId && $survey->answers()->where('user_id', $userId)->exists()) {
            return redirect()->route('surveys.index')
                ->with('info', 'Vous avez déjà répondu à ce sondage.');
        }

        $answers = $request->input('answers', []);

        foreach ($survey->questions as $question) {
            $answerValue = $answers[$question->id] ?? null;
            if ($answerValue === null) continue;

            if (is_array($answerValue)) {
                $answerValue = json_encode($answerValue);
            }

            \DB::table('survey_answers')->insert([
                'user_id' => $userId,
                'survey_question_id' => $question->id,
                'answer' => $answerValue,
                'survey_id' => $survey->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Sondage répondu !');
    }
    
    public function public(string $token)
    {
        // Decode token (recover survey ID)
        try {
            $surveyId = Crypt::decryptString($token);
        } catch (\Exception $e) {
            abort(404, "Lien invalide.");
        }
    
        // Retrieve survey
        $survey = Survey::findOrFail($surveyId);
    
        // Check active period
        $now = now();
        if (!($now->between($survey->start_date, $survey->end_date))) {
            abort(403, "Ce sondage n'est pas actif.");
        }
    
        // Display public view
        return view('surveys.public', compact('survey'));
    }
}




