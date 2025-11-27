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
use App\Actions\Survey\GetPublicSurveyAction;
use App\Actions\Survey\StoreSurveyAnswerAction;
use App\DTOs\SurveyAnswerDTO;

class SurveyController extends Controller
{

    /**
     * AFFICHE LA LISTE DES SONDAGES
     */
    public function index()
    {
    $user = auth()->user();
    $surveys = Survey::with('answers')->where('organization_id', 1)->get();

    foreach ($surveys as $survey) {
        $survey->hasAnswered = $survey->answers->contains(function($answer) use ($user) {
            return $answer->user_id === $user->id || $answer->user_id === null;
        });
    }

    return view('surveys.index', compact('surveys'));
    }

    /**
     * CREE UN SONDAGE ( PAGE FORMULAIRE)
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * CREEATION DU SONDAGE
     */
    public function store(StoreSurveyRequest $request)
    {
        $this->authorize('create', Survey::class);

        // Crée le DTO avec organisation 1
        $dto = SurveyDTO::fromRequest($request);
        $survey = app(StoreSurveyAction::class)->execute($dto);

        return redirect()->route('surveys.index')->with('success', 'Sondage créé !');
    }

    /**
     * EDITION D'UN SONDAGE
     */

    //afficher la page de l'edition
    public function edit(Survey $survey)
    {
        $this->authorize('update', $survey); 

        return view('surveys.edit', compact('survey'));
    }

    //met a jour la bdd
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $this->authorize('update', $survey); 

        $dto = SurveyDTO::fromRequest($request);
        app(UpdateSurveyAction::class)->execute($dto, $survey);

        return redirect()->route('surveys.index')->with('success', 'Sondage mis à jour !');
    }

    /**
     * SUPPRETION D'UN SONDAGE
     */
    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);

        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Sondage supprimé !');
    }

    /**
     * AJOUTER UNE QUESTION A UN SONDAGE
     */
    public function addQuestion(Request $request, Survey $survey)
    {
        $this->authorize('update', $survey);

        $dto = SurveyQuestionDTO::fromRequest($request, $survey->id);
        app(StoreSurveyQuestionAction::class)->execute($dto);

        return redirect()->route('surveys.index', $survey)
            ->with('success', 'Question ajoutée !');
    }

    /**
     * AJOUTER UNE QUESTION A UN SONDAGE (FORMULAIRE)
     */
    public function addQuestionForm(Survey $survey)
    {
        $this->authorize('update', $survey);

        return view('surveys.add_question', compact('survey'));
    }

    /**
     * MODIFIER LES QUESTIONS D'UN SONDAGE
     */
    public function editQuestions(Survey $survey)
    {
        $this->authorize('update', $survey);

        $questions = $survey->questions; // récupère toutes les questions
        return view('surveys.questions.edit', compact('survey', 'questions'));
    }

    /**
     * PAGE REPONDRE A UN SONDAGE
     */
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

    /**
     * MET A JOUR LES QUESTIONS DU SONDAGE
     */
    public function updateQuestions(Request $request, Survey $survey)
    {
        $questionsData = $request->input('questions', []);

        foreach ($questionsData as $id => $data) {
            $question = $survey->questions()->find($id);
            if (!$question) continue;

            // Transforme les options en array 
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

    /**
     * Submit survey answers.
     */
    public function submitSurvey(Request $request, Survey $survey, StoreSurveyAnswerAction $storeAction)
    {
        $userId = $request->has('respond_anonymously') 
            ? null 
            : $request->user()->id;

        if ($userId && $survey->answers()->where('user_id', $userId)->exists()) {
            return redirect()->route('surveys.index')
                ->with('info', 'Vous avez déjà répondu à ce sondage.');
        }

        $answers = $request->input('answers', []);

        foreach ($survey->questions as $question) {
            if (!isset($answers[$question->id])) {
                continue;
            }

            $dto = SurveyAnswerDTO::fromRequest($request, $survey, $question->id, $userId);
            $storeAction->execute($dto);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Sondage répondu !');
    }

    public function showPublicSurvey(string $token, GetPublicSurveyAction $action)
    {
        $survey = $action->execute($token);

        return view('surveys.public', compact('survey'));
    }

}




